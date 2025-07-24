<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\Exception;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\InvalidSignException;
use Pengxul\Supports\Collection;

use function Pengxul\Artful\should_do_http_request;
use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\verify_alipay_sign;

class VerifySignaturePlugin implements PluginInterface
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
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[Alipay][VerifySignaturePlugin] 插件开始装载', ['rocket' => $rocket]);

        if (!should_do_http_request($rocket->getDirection())) {
            return $rocket;
        }

        $destination = $rocket->getDestination();

        if ((!$destination instanceof Collection) || empty($result = $destination->except('_sign')->all())) {
            throw new InvalidParamsException(Exception::RESPONSE_EMPTY, '参数异常: 支付宝验证签名时待验签参数不正确', $destination);
        }

        $config = get_provider_config('alipay', $rocket->getParams());

        verify_alipay_sign($config, json_encode($result, JSON_UNESCAPED_UNICODE), $destination->get('_sign', ''));

        Logger::info('[Alipay][VerifySignaturePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }
}
