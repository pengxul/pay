<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Fund\Transfer;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://opendocs.alipay.com/open/30b94a2f_alipay.data.bill.ereceipt.query?pathHash=bfe1c3f4&ref=api&scene=common
 */
class QueryReceiptPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Fund][Transfer][QueryReceiptPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.data.bill.ereceipt.query',
            'biz_content' => $rocket->getParams(),
        ]);

        Logger::info('[Alipay][Fund][Transfer][QueryReceiptPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
