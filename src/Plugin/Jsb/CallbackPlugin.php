<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Jsb;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Direction\NoHttpRequestDirection;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidSignException;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\verify_jsb_sign;

class CallbackPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     * @throws InvalidSignException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[Jsb][CallbackPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->formatRequestAndParams($rocket);

        $params = $rocket->getParams();
        $config = get_provider_config('jsb', $params);

        $payload = $rocket->getPayload();
        $signature = $payload->get('sign');

        $payload->forget('sign');
        $payload->forget('signType');

        verify_jsb_sign($config, $payload->sortKeys()->toString(), $signature);

        $rocket->setDirection(NoHttpRequestDirection::class)
            ->setDestination($rocket->getPayload());

        Logger::info('[Jsb][CallbackPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws InvalidParamsException
     */
    protected function formatRequestAndParams(Rocket $rocket): void
    {
        $request = $rocket->getParams()['request'] ?? null;

        if (!$request instanceof Collection) {
            throw new InvalidParamsException(Exception::PARAMS_CALLBACK_REQUEST_INVALID);
        }

        $rocket->setPayload($request)->setParams($rocket->getParams()['params'] ?? []);
    }
}
