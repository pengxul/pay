<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Pay\Agreement\Sign;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://opendocs.alipay.com/open/b841da1f_alipay.user.agreement.unsign?pathHash=6d2d4910&ref=api&scene=common
 */
class UnsignPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Pay][Agreement][Sign][UnsignPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.user.agreement.unsign',
            'biz_content' => $rocket->getParams(),
        ]);

        Logger::info('[Alipay][Pay][Agreement][Sign][UnsignPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
