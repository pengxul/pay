<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\Ebpp;

use Closure;
use Pengxul\Pay\Contract\PluginInterface;
use Pengxul\Pay\Logger;
use Pengxul\Pay\Rocket;

/**
 * @see https://opendocs.alipay.com/open/02hd35
 */
class PdeductPayPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][PdeductPayPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.ebpp.pdeduct.pay',
            'biz_content' => array_merge(
                [
                    'agent_channel' => 'PUBLICFORM',
                ],
                $rocket->getParams(),
            ),
        ]);

        Logger::info('[alipay][PdeductPayPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
