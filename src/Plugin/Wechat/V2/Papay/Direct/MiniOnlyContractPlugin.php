<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V2\Papay\Direct;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Direction\NoHttpRequestDirection;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\get_wechat_type_key;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/api/wxpay_v2/papay/chapter3_3.shtml
 */
class MiniOnlyContractPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V2][Papay][Direct][OnlyContractPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('wechat', $params);
        $payload = $rocket->getPayload();

        $rocket->setDirection(NoHttpRequestDirection::class)
            ->mergePayload([
                'appid' => $config[get_wechat_type_key($params)] ?? '',
                'mch_id' => $config['mch_id'] ?? '',
                'notify_url' => $payload?->get('notify_url') ?? $config['notify_url'] ?? '',
                'timestamp' => time(),
            ]);

        Logger::info('[Wechat][V2][Papay][Direct][OnlyContractPlugin] 插件装载完毕', ['rocket' => $rocket]);

        /** @var Rocket $rocket */
        $rocket = $next($rocket);

        $rocket->setDestination($rocket->getPayload());

        return $rocket;
    }
}
