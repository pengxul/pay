<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Member\Ocr;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://opendocs.alipay.com/open/0aggs5?pathHash=084101d3&ref=api
 */
class DetectPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Member][Ocr][DetectPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'datadigital.fincloud.generalsaas.ocr.common.detect',
            'biz_content' => $rocket->getParams(),
        ]);

        Logger::info('[Alipay][Member][Ocr][DetectPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
