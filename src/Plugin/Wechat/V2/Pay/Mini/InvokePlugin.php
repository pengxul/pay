<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V2\Pay\Mini;

use Closure;
use Throwable;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\InvalidResponseException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Pay;
use Pengxul\Supports\Collection;
use Pengxul\Supports\Config;
use Pengxul\Supports\Str;

use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\get_wechat_sign_v2;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/api/wxa/wxa_api.php?chapter=7_7&index=5
 */
class InvokePlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws Exception
     * @throws InvalidResponseException
     * @throws ServiceNotFoundException
     * @throws Throwable                生成随机串失败
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[Wechat][V2][Pay][Mini][InvokePlugin] 插件开始装载', ['rocket' => $rocket]);

        $destination = $rocket->getDestination();
        $prepayId = $destination?->get('prepay_id') ?? null;

        if (is_null($prepayId)) {
            Logger::error('[Wechat][V2][Pay][Mini][InvokePlugin] 预下单失败：响应缺少 `prepay_id` 参数，请自行检查参数是否符合微信要求', $destination?->all() ?? null);

            throw new InvalidResponseException(Exception::RESPONSE_MISSING_NECESSARY_PARAMS, $destination?->get('message') ?? '预下单失败：响应缺少 `prepay_id` 参数，请自行检查参数是否符合微信要求', $destination?->all() ?? null);
        }

        $params = $rocket->getParams();
        $config = get_provider_config('wechat', $params);
        $payload = $rocket->getPayload();

        $rocket->setDestination($this->getInvokeConfig($payload, $config, $prepayId));

        Logger::info('[Wechat][V2][Pay][Mini][InvokePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable              生成随机串失败
     */
    protected function getInvokeConfig(?Collection $payload, array $config, string $prepayId): Config
    {
        $invokeConfig = new Config([
            'appId' => $this->getAppId($payload, $config),
            'timeStamp' => time().'',
            'nonceStr' => Str::random(32),
            'package' => 'prepay_id='.$prepayId,
            'signType' => 'MD5',
        ]);

        $invokeConfig->set('paySign', get_wechat_sign_v2($config, $invokeConfig->all()));

        return $invokeConfig;
    }

    protected function getAppId(?Collection $payload, array $config): string
    {
        if (Pay::MODE_SERVICE === ($config['mode'] ?? Pay::MODE_NORMAL)) {
            return $payload?->get('_invoke_appid') ?? $config['sub_mini_app_id'] ?? '';
        }

        return $payload?->get('_invoke_appid') ?? $config['mini_app_id'] ?? '';
    }
}
