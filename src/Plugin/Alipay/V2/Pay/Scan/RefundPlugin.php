<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Pay\Scan;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://opendocs.alipay.com/open/02ekfk?pathHash=b45b14f7&ref=api&scene=common
 */
class RefundPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Pay][Scan][RefundPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.trade.refund',
            'biz_content' => $rocket->getParams(),
        ]);

        Logger::info('[Alipay][Pay][Scan][RefundPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
