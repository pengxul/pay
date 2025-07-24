<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay;

use Closure;
use Pengxul\Pay\Contract\PluginInterface;
use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidConfigException;
use Pengxul\Pay\Exception\InvalidResponseException;
use Pengxul\Pay\Exception\ServiceNotFoundException;
use Pengxul\Pay\Logger;
use Pengxul\Pay\Rocket;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\should_do_http_request;
use function Pengxul\Pay\verify_alipay_sign;

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

        Logger::debug('[alipay][LaunchPlugin] 插件开始装载', ['rocket' => $rocket]);

        if (should_do_http_request($rocket->getDirection())) {
            $response = Collection::wrap($rocket->getDestination());
            $result = $response->get($this->getResultKey($rocket->getPayload()));

            $this->verifySign($rocket->getParams(), $response, $result);

            $rocket->setDestination(Collection::wrap($result));
        }

        Logger::info('[alipay][LaunchPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidResponseException
     * @throws ServiceNotFoundException
     */
    protected function verifySign(array $params, Collection $response, ?array $result): void
    {
        $sign = $response->get('sign', '');

        if ('' === $sign || is_null($result)) {
            throw new InvalidResponseException(Exception::INVALID_RESPONSE_SIGN, 'Verify Alipay Response Sign Failed: sign is empty', $response);
        }

        verify_alipay_sign($params, json_encode($result, JSON_UNESCAPED_UNICODE), $sign);
    }

    protected function getResultKey(Collection $payload): string
    {
        $method = $payload->get('method');

        return str_replace('.', '_', $method).'_response';
    }
}
