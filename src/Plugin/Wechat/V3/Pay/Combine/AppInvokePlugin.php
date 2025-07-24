<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Pay\Combine;

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
use function Pengxul\Pay\get_wechat_sign;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/combine-payment/orders/app-transfer-payment.html
 * @see https://pay.weixin.qq.com/docs/partner/apis/combine-payment/orders/app-transfer-payment.html
 */
class AppInvokePlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidResponseException
     * @throws ServiceNotFoundException
     * @throws Throwable                生成随机串失败
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[Wechat][Pay][Combine][AppInvokePlugin] 插件开始装载', ['rocket' => $rocket]);

        $destination = $rocket->getDestination();
        $prepayId = $destination?->get('prepay_id');

        if (is_null($prepayId)) {
            Logger::error('[Wechat][Pay][Combine][AppInvokePlugin] 预下单失败：响应缺少 `prepay_id` 参数，请自行检查参数是否符合微信要求', $destination?->all() ?? null);

            throw new InvalidResponseException(Exception::RESPONSE_MISSING_NECESSARY_PARAMS, $destination?->get('message') ?? '预下单失败：响应缺少 `prepay_id` 参数，请自行检查参数是否符合微信要求', $destination?->all() ?? null);
        }

        $params = $rocket->getParams();
        $config = get_provider_config('wechat', $params);
        $payload = $rocket->getPayload();

        $rocket->setDestination($this->getInvokeConfig($payload, $config, $prepayId));

        Logger::info('[Wechat][Pay][Combine][AppInvokePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }

    /**
     * @throws InvalidConfigException
     * @throws Throwable              生成随机串失败
     */
    protected function getInvokeConfig(?Collection $payload, array $config, string $prepayId): Config
    {
        $invokeConfig = new Config([
            'appid' => $this->getAppId($payload, $config),
            'partnerid' => $this->getPartnerId($payload, $config),
            'prepayid' => $prepayId,
            'package' => 'Sign=WXPay',
            'noncestr' => Str::random(32),
            'timestamp' => time().'',
        ]);

        $invokeConfig->set('sign', $this->getSign($invokeConfig, $config));

        return $invokeConfig;
    }

    /**
     * @throws InvalidConfigException
     */
    protected function getSign(Collection $invokeConfig, array $config): string
    {
        $contents = $invokeConfig->get('appid', '')."\n"
            .$invokeConfig->get('timestamp', '')."\n"
            .$invokeConfig->get('noncestr', '')."\n"
            .$invokeConfig->get('prepayid', '')."\n";

        return get_wechat_sign($config, $contents);
    }

    protected function getAppId(?Collection $payload, array $config): string
    {
        if (Pay::MODE_SERVICE === ($config['mode'] ?? Pay::MODE_NORMAL)) {
            return $payload?->get('_invoke_appid') ?? $config['sub_app_id'] ?? '';
        }

        return $payload?->get('_invoke_appid') ?? $config['app_id'] ?? '';
    }

    protected function getPartnerId(?Collection $payload, array $config): string
    {
        return $payload?->get('_invoke_partnerid') ?? $config['mch_id'] ?? '';
    }
}
