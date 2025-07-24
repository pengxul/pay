<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay;

use Closure;
use Pengxul\Pay\Contract\PluginInterface;
use Pengxul\Pay\Logger;
use Pengxul\Pay\Rocket;

abstract class GeneralPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][GeneralPlugin] 通用插件开始装载', ['rocket' => $rocket]);

        $this->doSomethingBefore($rocket);

        $rocket->mergePayload([
            'method' => $this->getMethod(),
            'biz_content' => $rocket->getParams(),
        ]);

        Logger::info('[alipay][GeneralPlugin] 通用插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function doSomethingBefore(Rocket $rocket): void
    {
    }

    abstract protected function getMethod(): string;
}
