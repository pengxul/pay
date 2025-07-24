<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

use function Pengxul\Artful\filter_params;

class FormatPayloadBizContentPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][FormatPayloadBizContentPlugin] 插件开始装载', ['rocket' => $rocket]);

        $payload = $rocket->getPayload();

        $rocket->setPayload(
            filter_params($payload->all(), fn ($k, $v) => '' !== $v && 'sign' != $k)
                ->merge([
                    'biz_content' => json_encode(filter_params($payload->get('biz_content', []))),
                ])
        );

        Logger::info('[Alipay][FormatPayloadBizContentPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
