<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Marketing\Fapiao;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

use function Pengxul\Pay\get_provider_config;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/products/fapiao/apilist.html
 */
class CreateCardTemplatePlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V3][Marketing][Fapiao][CreateCardTemplatePlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('wechat', $params);
        $payload = $rocket->getPayload();

        $rocket->mergePayload([
            '_method' => 'POST',
            '_url' => 'v3/new-tax-control-fapiao/card-template',
            'card_appid' => $payload?->get('card_appid') ?? $config['mp_app_id'],
        ]);

        Logger::info('[Wechat][V3][Marketing][Fapiao][CreateCardTemplatePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
