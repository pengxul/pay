<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Marketing\Fapiao\Blockchain;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/fapiao/fapiao-merchant/get-merchant-info.html
 */
class GetBaseInformationPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V3][Marketing][Fapiao][Blockchain][GetBaseInformationPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->setPayload([
            '_method' => 'GET',
            '_url' => 'v3/new-tax-control-fapiao/merchant/base-information',
        ]);

        Logger::info('[Wechat][V3][Marketing][Fapiao][Blockchain][GetBaseInformationPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
