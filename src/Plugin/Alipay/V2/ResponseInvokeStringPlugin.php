<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2;

use Closure;
use GuzzleHttp\Psr7\Response;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Supports\Arr;

class ResponseInvokeStringPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[Alipay][ResponseInvokeStringPlugin] 插件开始装载', ['rocket' => $rocket]);

        $response = new Response(200, [], Arr::query($rocket->getPayload()->all()));

        $rocket->setDestination($response);

        Logger::info('[Alipay][ResponseInvokeStringPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }
}
