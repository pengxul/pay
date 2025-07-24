<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3;

use Closure;
use Psr\Http\Message\ServerRequestInterface;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Direction\NoHttpRequestDirection;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\DecryptException;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidSignException;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\decrypt_wechat_resource;
use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\verify_wechat_sign;

class CallbackPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws InvalidSignException
     * @throws ServiceNotFoundException
     * @throws DecryptException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V3][CallbackPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->init($rocket);

        $params = $rocket->getParams();

        /* @phpstan-ignore-next-line */
        verify_wechat_sign($rocket->getDestinationOrigin(), $params);

        $body = json_decode((string) $rocket->getDestination()->getBody(), true);

        $rocket->setDirection(NoHttpRequestDirection::class)->setPayload(new Collection($body));

        $body['resource'] = decrypt_wechat_resource($body['resource'] ?? [], get_provider_config('wechat', $params));

        $rocket->setDestination(new Collection($body));

        Logger::info('[Wechat][V3][CallbackPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws InvalidParamsException
     */
    protected function init(Rocket $rocket): void
    {
        $request = $rocket->getParams()['_request'] ?? null;
        $params = $rocket->getParams()['_params'] ?? [];

        if (!$request instanceof ServerRequestInterface) {
            throw new InvalidParamsException(Exception::PARAMS_CALLBACK_REQUEST_INVALID, '参数异常: 微信回调参数不正确');
        }

        $rocket->setDestination(clone $request)
            ->setDestinationOrigin($request)
            ->setParams($params);
    }
}
