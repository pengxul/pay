<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\DecryptException;
use Pengxul\Pay\Exception\InvalidSignException;

use function Pengxul\Artful\should_do_http_request;
use function Pengxul\Pay\verify_wechat_sign;

class VerifySignaturePlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     * @throws ServiceNotFoundException
     * @throws DecryptException
     * @throws InvalidParamsException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[Wechat][V3][VerifySignaturePlugin] 插件开始装载', ['rocket' => $rocket]);

        if (!should_do_http_request($rocket->getDirection()) || is_null($rocket->getDestinationOrigin())) {
            return $rocket;
        }

        verify_wechat_sign($rocket->getDestinationOrigin(), $rocket->getParams());

        Logger::info('[Wechat][V3][VerifySignaturePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }
}
