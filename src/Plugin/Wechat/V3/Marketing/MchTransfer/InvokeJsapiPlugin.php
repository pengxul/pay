<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Marketing\MchTransfer;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\InvalidResponseException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Pay;
use Pengxul\Supports\Collection;
use Pengxul\Supports\Config;

use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\get_wechat_type_key;

/**
 * @see https://pay.weixin.qq.com/doc/v3/merchant/4012716430
 */
class InvokeJsapiPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidResponseException
     * @throws ServiceNotFoundException
     * @throws InvalidParamsException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[Wechat][V3][Marketing][MchTransfer][InvokeJsapiPlugin] 插件开始装载', ['rocket' => $rocket]);

        $config = get_provider_config('wechat', $rocket->getParams());
        $destination = $rocket->getDestination();
        $packageInfo = $destination?->get('package_info');

        if (Pay::MODE_SERVICE === ($config['mode'] ?? Pay::MODE_NORMAL)) {
            throw new InvalidParamsException(Exception::PARAMS_PLUGIN_ONLY_SUPPORT_NORMAL_MODE, '参数异常: JSAPI调起用户确认收款，只支持普通商户模式，当前配置为服务商模式');
        }

        if (is_null($packageInfo)) {
            Logger::error('[Wechat][V3][Marketing][MchTransfer][InvokeJsapiPlugin] JSAPI调起用户确认收款失败：响应缺少 `package_info` 参数，请自行检查参数是否符合微信要求', $destination?->all() ?? null);

            throw new InvalidResponseException(Exception::RESPONSE_MISSING_NECESSARY_PARAMS, $destination?->get('fail_reason') ?? 'JSAPI调起用户确认收款失败：响应缺少 `package_info` 参数，请自行检查参数是否符合微信要求', $destination?->all() ?? null);
        }

        $params = $rocket->getParams();
        $config = get_provider_config('wechat', $params);
        $payload = $rocket->getPayload();

        $rocket->setDestination($this->getInvokeConfig($payload, $params, $config, $packageInfo));

        Logger::info('[Wechat][V3][Marketing][MchTransfer][InvokeJsapiPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }

    protected function getInvokeConfig(?Collection $payload, array $params, array $config, string $packageInfo): Config
    {
        return new Config([
            'appId' => $payload?->get('_invoke_appId') ?? $config[get_wechat_type_key($params)] ?? '',
            'mchId' => $payload?->get('_invoke_mchId') ?? $config['mch_id'] ?? '',
            'package' => $packageInfo,
        ]);
    }
}
