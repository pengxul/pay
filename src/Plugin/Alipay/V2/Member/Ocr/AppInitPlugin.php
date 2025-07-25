<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Member\Ocr;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://opendocs.alipay.com/open/043ksf?pathHash=ea0482cd&ref=api&scene=common
 */
class AppInitPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Member][Ocr][AppInitPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'datadigital.fincloud.generalsaas.ocr.mobile.initialize',
            'biz_content' => array_merge(
                [
                    'biz_code' => 'DATA_DIGITAL_BIZ_CODE_OCR',
                ],
                $rocket->getParams(),
            ),
        ]);

        Logger::info('[Alipay][Member][Ocr][AppInitPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
