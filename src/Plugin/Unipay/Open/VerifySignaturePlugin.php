<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Unipay\Open;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\InvalidSignException;
use Pengxul\Supports\Collection;

use function Pengxul\Artful\should_do_http_request;
use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\verify_unipay_sign;

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

        Logger::debug('[Unipay][VerifySignaturePlugin] 插件开始装载', ['rocket' => $rocket]);

        if (!should_do_http_request($rocket->getDirection())) {
            return $rocket;
        }

        $destination = $rocket->getDestination();

        if (!$destination instanceof Collection) {
            return $rocket;
        }

        $params = $rocket->getParams();
        $config = get_provider_config('unipay', $params);

        verify_unipay_sign(
            $config,
            $destination->except('signature')->sortKeys()->toString(),
            $destination->get('signature', ''),
            $destination->get('signPubKeyCert')
        );

        Logger::info('[Unipay][VerifySignaturePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }
}
