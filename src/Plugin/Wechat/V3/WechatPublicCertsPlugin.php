<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

class WechatPublicCertsPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V3][WechatPublicCertsPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->setPayload([
            '_method' => 'GET',
            '_url' => 'v3/certificates',
        ]);

        Logger::info('[Wechat][V3][WechatPublicCertsPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
