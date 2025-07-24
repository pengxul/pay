<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Pay\Agreement\Sign;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://opendocs.alipay.com/open/8bccfa0b_alipay.user.agreement.page.sign?pathHash=725a0634&ref=api&scene=common
 */
class SignPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Pay][Agreement][Sign][SignPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->setDirection(ResponseDirection::class)
            ->mergePayload([
                'method' => 'alipay.user.agreement.page.sign',
                'biz_content' => $rocket->getParams(),
            ]);

        Logger::info('[Alipay][Pay][Agreement][Sign][SignPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
