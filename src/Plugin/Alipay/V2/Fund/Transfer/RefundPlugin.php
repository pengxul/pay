<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Fund\Transfer;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://opendocs.alipay.com/open/02byvd?pathHash=2dceb6d2&ref=api&scene=common
 */
class RefundPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Fund][Transfer][RefundPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.fund.trans.refund',
            'biz_content' => $rocket->getParams(),
        ]);

        Logger::info('[Alipay][Fund][Transfer][RefundPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
