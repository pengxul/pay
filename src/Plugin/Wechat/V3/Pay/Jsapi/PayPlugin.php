<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Pay\Jsapi;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Pay;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\get_wechat_type_key;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/jsapi-payment/direct-jsons/jsapi-prepay.html
 * @see https://pay.weixin.qq.com/docs/partner/apis/partner-jsapi-payment/partner-jsons/partner-jsapi-prepay.html
 */
class PayPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V3][Pay][Jsapi][PayPlugin] 插件开始装载', ['rocket' => $rocket]);

        $payload = $rocket->getPayload();
        $params = $rocket->getParams();
        $config = get_provider_config('wechat', $params);

        if (is_null($payload)) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: Jsapi 下单，参数为空');
        }

        if (Pay::MODE_SERVICE === ($config['mode'] ?? Pay::MODE_NORMAL)) {
            $data = $this->service($payload, $config, $params);
        }

        $rocket->mergePayload(array_merge(
            [
                '_method' => 'POST',
                '_url' => 'v3/pay/transactions/jsapi',
                '_service_url' => 'v3/pay/partner/transactions/jsapi',
                'notify_url' => $payload->get('notify_url', $config['notify_url'] ?? ''),
            ],
            $data ?? $this->normal($config, $params)
        ));

        Logger::info('[Wechat][V3][Pay][Jsapi][PayPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function normal(array $config, array $params): array
    {
        return [
            'appid' => $config[get_wechat_type_key($params)] ?? '',
            'mchid' => $config['mch_id'] ?? '',
        ];
    }

    protected function service(Collection $payload, array $config, array $params): array
    {
        $wechatTypeKey = get_wechat_type_key($params);

        $data = [
            'sp_appid' => $config[$wechatTypeKey] ?? '',
            'sp_mchid' => $config['mch_id'] ?? '',
            'sub_mchid' => $payload->get('sub_mchid', $config['sub_mch_id'] ?? ''),
        ];

        if ($payload->has('payer.sub_openid')) {
            $data['sub_appid'] = $config['sub_'.$wechatTypeKey] ?? '';
        }

        return $data;
    }
}
