<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat;

use Closure;
use JetBrains\PhpStorm\Deprecated;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

#[Deprecated(reason: '自 v3.7.5 版本已废弃', replacement: '`yansongda/artful` 包中的 `Pengxul\Artful\Plugin\StartPlugin`')]
class StartPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][StartPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload($rocket->getParams());

        Logger::info('[Wechat][StartPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
