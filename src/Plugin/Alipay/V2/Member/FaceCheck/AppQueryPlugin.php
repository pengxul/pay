<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Member\FaceCheck;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://opendocs.alipay.com/open/03nisv?pathHash=3f259e83&ref=api&scene=common
 */
class AppQueryPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Member][FaceCheck][AppQueryPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'datadigital.fincloud.generalsaas.face.check.query',
            'biz_content' => $rocket->getParams(),
        ]);

        Logger::info('[Alipay][Member][FaceCheck][AppQueryPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
