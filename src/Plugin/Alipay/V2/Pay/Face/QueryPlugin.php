<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Pay\Face;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://opendocs.alipay.com/open/c8e4d285_zoloz.authentication.customer.ftoken.query?pathHash=4cba522d&ref=api&scene=common
 */
class QueryPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Pay][Face][QueryPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'zoloz.authentication.customer.ftoken.query',
            'biz_content' => $rocket->getParams(),
        ]);

        Logger::info('[Alipay][Pay][Face][QueryPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
