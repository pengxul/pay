<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Extend\Complaints;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/consumer-complaint/complaint-notifications/create-complaint-notifications.html
 * @see https://pay.weixin.qq.com/docs/partner/apis/consumer-complaint/complaint-notifications/create-complaint-notifications.html
 */
class SetCallbackPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][Extend][Complaints][SetCallbackPlugin] 插件开始装载', ['rocket' => $rocket]);

        $payload = $rocket->getPayload();

        $rocket->setPayload([
            '_method' => 'POST',
            '_url' => 'v3/merchant-service/complaint-notifications',
            '_service_url' => 'v3/merchant-service/complaint-notifications',
            'url' => $payload?->get('url', '') ?? '',
        ]);

        Logger::info('[Wechat][Extend][Complaints][SetCallbackPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
