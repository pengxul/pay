<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Unipay;

use Closure;
use Pengxul\Pay\Contract\PluginInterface;
use Pengxul\Pay\Direction\NoHttpRequestDirection;
use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidConfigException;
use Pengxul\Pay\Exception\InvalidResponseException;
use Pengxul\Pay\Exception\ServiceNotFoundException;
use Pengxul\Pay\Logger;
use Pengxul\Pay\Rocket;
use Pengxul\Supports\Collection;
use Pengxul\Supports\Str;

use function Pengxul\Pay\verify_unipay_sign;

class CallbackPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     * @throws InvalidResponseException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[unipay][CallbackPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->formatPayload($rocket);

        $params = $rocket->getParams();
        $signature = $params['signature'] ?? false;

        if (!$signature) {
            throw new InvalidResponseException(Exception::INVALID_RESPONSE_SIGN, '', $params);
        }

        verify_unipay_sign($params, $rocket->getPayload()->sortKeys()->toString(), $signature);

        $rocket->setDirection(NoHttpRequestDirection::class)
            ->setDestination($rocket->getPayload())
        ;

        Logger::info('[unipay][CallbackPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function formatPayload(Rocket $rocket): void
    {
        $payload = (new Collection($rocket->getParams()))
            ->filter(fn ($v, $k) => 'signature' != $k && !Str::startsWith($k, '_'))
        ;

        $rocket->setPayload($payload);
    }
}
