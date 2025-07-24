<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Pay\Authorization\Auth;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://opendocs.alipay.com/open/064jhe?pathHash=629fa9a5&ref=api&scene=2a9ad7e9012248b0acd5edd04c9f31b6
 */
class AppFreezePlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Pay][Authorization][Auth][AppFreezePlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->setDirection(ResponseDirection::class)
            ->mergePayload([
                'method' => 'alipay.fund.auth.order.app.freeze',
                'biz_content' => array_merge(
                    [
                        'product_code' => 'PREAUTH_PAY',
                    ],
                    $rocket->getParams()
                ),
            ]);

        Logger::info('[Alipay][Pay][Authorization][Auth][AppFreezePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
