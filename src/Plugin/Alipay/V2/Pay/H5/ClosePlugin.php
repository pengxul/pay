<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Pay\H5;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://opendocs.alipay.com/open/a62e8677_alipay.trade.close?pathHash=0801e763&ref=api&scene=common
 */
class ClosePlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Pay][H5][ClosePlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.trade.close',
            'biz_content' => $rocket->getParams(),
        ]);

        Logger::info('[Alipay][Pay][H5][ClosePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
