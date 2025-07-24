<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2;

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

use function Pengxul\Artful\filter_params;
use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\verify_alipay_sign;

class AppCallbackPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     * @throws InvalidSignException
     * @throws InvalidParamsException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][AppCallbackPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('alipay', $params);

        if (empty($params['alipay_trade_app_pay_response'])) {
            throw new InvalidParamsException(Exception::PARAMS_CALLBACK_REQUEST_INVALID);
        }

        $value = filter_params($params['alipay_trade_app_pay_response']);

        verify_alipay_sign($config, $value->toJson(), $params['sign'] ?? '');

        $rocket->setPayload($params)
            ->setDirection(NoHttpRequestDirection::class)
            ->setDestination($rocket->getPayload());

        Logger::info('[Alipay][AppCallbackPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
