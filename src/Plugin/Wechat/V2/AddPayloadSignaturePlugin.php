<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V2;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

use function Pengxul\Artful\filter_params;
use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\get_wechat_sign_v2;

class AddPayloadSignaturePlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V2][AddPayloadSignaturePlugin] 插件开始装载', ['rocket' => $rocket]);

        $config = get_provider_config('wechat', $rocket->getParams());

        $rocket->mergePayload([
            'sign' => get_wechat_sign_v2($config, filter_params($rocket->getPayload())->all()),
        ]);

        Logger::info('[Wechat][V2][AddPayloadSignaturePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
