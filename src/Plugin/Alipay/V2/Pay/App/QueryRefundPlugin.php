<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Pay\App;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://opendocs.alipay.com/open/8c776df6_alipay.trade.fastpay.refund.query?pathHash=fb6e1894&ref=api&scene=common
 */
class QueryRefundPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Pay][App][QueryRefundPlugin] 通用插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.trade.fastpay.refund.query',
            'biz_content' => $rocket->getParams(),
        ]);

        Logger::info('[Alipay][Pay][App][QueryRefundPlugin] 通用插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
