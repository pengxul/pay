<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Unipay;

use Closure;
use Pengxul\Pay\Contract\PluginInterface;
use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Exception\InvalidConfigException;
use Pengxul\Pay\Exception\InvalidResponseException;
use Pengxul\Pay\Exception\ServiceNotFoundException;
use Pengxul\Pay\Logger;
use Pengxul\Pay\Rocket;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\should_do_http_request;
use function Pengxul\Pay\verify_unipay_sign;

class LaunchPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidResponseException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[unipay][LaunchPlugin] 插件开始装载', ['rocket' => $rocket]);

        if (should_do_http_request($rocket->getDirection())) {
            $response = Collection::wrap($rocket->getDestination());
            $signature = $response->get('signature');
            $response->forget('signature');

            verify_unipay_sign(
                $rocket->getParams(),
                $response->sortKeys()->toString(),
                $signature
            );

            $rocket->setDestination($response);
        }

        Logger::info('[unipay][LaunchPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }
}
