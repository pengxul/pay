<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Unipay\Qra;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\InvalidSignException;

use function Pengxul\Artful\should_do_http_request;
use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\verify_unipay_sign_qra;

class VerifySignaturePlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[Unipay][Qra][VerifySignaturePlugin] 插件开始装载', ['rocket' => $rocket]);

        $config = get_provider_config('unipay', $rocket->getParams());

        if (!should_do_http_request($rocket->getDirection())) {
            return $rocket;
        }

        verify_unipay_sign_qra($config, $rocket->getDestination()?->all() ?? []);

        Logger::info('[Unipay][Qra][VerifySignaturePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }
}
