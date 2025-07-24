<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat;

use Closure;
use Psr\Http\Message\ServerRequestInterface;
use Pengxul\Pay\Contract\PluginInterface;
use Pengxul\Pay\Direction\NoHttpRequestDirection;
use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidConfigException;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Exception\InvalidResponseException;
use Pengxul\Pay\Exception\ServiceNotFoundException;
use Pengxul\Pay\Logger;
use Pengxul\Pay\Rocket;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\decrypt_wechat_resource;
use function Pengxul\Pay\verify_wechat_sign;

class CallbackPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws ServiceNotFoundException
     * @throws InvalidResponseException
     * @throws InvalidParamsException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[wechat][CallbackPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->formatRequestAndParams($rocket);

        /* @phpstan-ignore-next-line */
        verify_wechat_sign($rocket->getDestinationOrigin(), $rocket->getParams());

        $body = json_decode((string) $rocket->getDestination()->getBody(), true);

        $rocket->setDirection(NoHttpRequestDirection::class)->setPayload(new Collection($body));

        $body['resource'] = decrypt_wechat_resource($body['resource'] ?? [], $rocket->getParams());

        $rocket->setDestination(new Collection($body));

        Logger::info('[wechat][CallbackPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws InvalidParamsException
     */
    protected function formatRequestAndParams(Rocket $rocket): void
    {
        $request = $rocket->getParams()['request'] ?? null;

        if (!$request instanceof ServerRequestInterface) {
            throw new InvalidParamsException(Exception::REQUEST_NULL_ERROR);
        }

        $rocket->setDestination(clone $request)
            ->setDestinationOrigin($request)
            ->setParams($rocket->getParams()['params'] ?? [])
        ;
    }
}
