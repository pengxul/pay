<?php

declare(strict_types=1);

namespace Pengxul\Pay\Provider;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Pengxul\Artful\Artful;
use Pengxul\Artful\Event;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Contract\ProviderInterface;
use Pengxul\Pay\Event\CallbackReceived;
use Pengxul\Pay\Event\MethodCalled;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Jsb\AddPayloadSignPlugin;
use Pengxul\Pay\Plugin\Jsb\AddRadarPlugin;
use Pengxul\Pay\Plugin\Jsb\CallbackPlugin;
use Pengxul\Pay\Plugin\Jsb\ResponsePlugin;
use Pengxul\Pay\Plugin\Jsb\StartPlugin;
use Pengxul\Pay\Plugin\Jsb\VerifySignaturePlugin;
use Pengxul\Supports\Collection;
use Pengxul\Supports\Str;

/**
 * @method Collection|Rocket scan(array $order) 扫码支付[微信支付宝都可扫描]
 */
class Jsb implements ProviderInterface
{
    public const URL = [
        Pay::MODE_NORMAL => 'https://mybank.jsbchina.cn:577/eis/merchant/merchantServices.htm',
        Pay::MODE_SANDBOX => 'https://epaytest.jsbchina.cn:9999/eis/merchant/merchantServices.htm',
    ];

    /**
     * @param mixed $name
     * @param mixed $params
     *
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function __call($name, $params): null|Collection|MessageInterface|Rocket
    {
        $plugin = '\Pengxul\Pay\Shortcut\Jsb\\'.Str::studly($name).'Shortcut';

        return Artful::shortcut($plugin, ...$params);
    }

    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     */
    public function pay(array $plugins, array $params): null|Collection|MessageInterface|Rocket
    {
        return Artful::artful($plugins, $params);
    }

    public function mergeCommonPlugins(array $plugins): array
    {
        return array_merge(
            [StartPlugin::class],
            $plugins,
            [AddPayloadSignPlugin::class, AddRadarPlugin::class, VerifySignaturePlugin::class, ResponsePlugin::class, ParserPlugin::class],
        );
    }

    /**
     * @throws InvalidParamsException
     */
    public function cancel(array $order): Collection|Rocket
    {
        throw new InvalidParamsException(Exception::PARAMS_METHOD_NOT_SUPPORTED, 'Jsb does not support cancel api');
    }

    /**
     * @throws InvalidParamsException
     */
    public function close(array $order): Collection|Rocket
    {
        throw new InvalidParamsException(Exception::PARAMS_METHOD_NOT_SUPPORTED, 'Jsb does not support close api');
    }

    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function refund(array $order): Collection|Rocket
    {
        Event::dispatch(new MethodCalled('jsb', __METHOD__, $order, null));

        return $this->__call('refund', [$order]);
    }

    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     */
    public function callback(null|array|ServerRequestInterface $contents = null, ?array $params = null): Collection|Rocket
    {
        $request = $this->getCallbackParams($contents);

        Event::dispatch(new CallbackReceived('jsb', $request->all(), $params, null));

        return $this->pay(
            [CallbackPlugin::class],
            ['request' => $request, 'params' => $params]
        );
    }

    public function success(): ResponseInterface
    {
        return new Response(
            200,
            ['Content-Type' => 'text/html'],
            'success',
        );
    }

    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function query(array $order): Collection|Rocket
    {
        Event::dispatch(new MethodCalled('jsb', __METHOD__, $order, null));

        return $this->__call('query', [$order]);
    }

    protected function getCallbackParams($contents = null): Collection
    {
        if (is_array($contents)) {
            return Collection::wrap($contents);
        }

        if ($contents instanceof ServerRequestInterface) {
            return Collection::wrap($contents->getParsedBody());
        }

        $request = ServerRequest::fromGlobals();

        return Collection::wrap($request->getParsedBody());
    }
}
