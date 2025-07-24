<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Unipay\Qra;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Direction\NoHttpRequestDirection;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\InvalidSignException;

use function Pengxul\Artful\filter_params;
use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\verify_unipay_sign_qra;

class CallbackPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Unipay][Qra][CallbackPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('unipay', $params);
        $destination = filter_params($params);

        if (isset($params['status']) && 0 == $params['status']) {
            verify_unipay_sign_qra($config, $destination->all());
        }

        $rocket->setPayload($params)
            ->setDirection(NoHttpRequestDirection::class)
            ->setDestination($destination);

        Logger::info('[Unipay][Qra][CallbackPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
