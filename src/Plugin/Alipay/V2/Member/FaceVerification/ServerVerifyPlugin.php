<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Member\FaceVerification;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://opendocs.alipay.com/open/04pxq6?pathHash=f421977b&ref=api&scene=common
 */
class ServerVerifyPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Member][FaceVerification][ServerVerifyPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'datadigital.fincloud.generalsaas.face.source.certify',
            'biz_content' => array_merge(
                [
                    'cert_type' => 'IDENTITY_CARD',
                ],
                $rocket->getParams()
            ),
        ]);

        Logger::info('[Alipay][Member][FaceVerification][ServerVerifyPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
