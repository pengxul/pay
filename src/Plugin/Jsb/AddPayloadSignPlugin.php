<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Jsb;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\get_private_cert;
use function Pengxul\Pay\get_provider_config;

class AddPayloadSignPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[Jsb][AddPayloadSignPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('jsb', $params);
        $payload = $rocket->getPayload();

        if (empty($payload) || $payload->isEmpty()) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 缺少支付必要参数。可能插件用错顺序，应该先使用 `业务插件`');
        }

        $privateCertPath = $config['mch_secret_cert_path'] ?? '';

        if (empty($privateCertPath)) {
            throw new InvalidConfigException(Exception::CONFIG_JSB_INVALID, '配置异常: 缺少配置参数 --  [mch_secret_cert_path]');
        }

        $rocket->mergePayload([
            'signType' => 'RSA',
            'sign' => $this->getSignature(get_private_cert($privateCertPath), $payload),
        ]);

        Logger::info('[Jsb][AddPayloadSignPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function getSignature(string $pkey, Collection $payload): string
    {
        $content = $payload->sortKeys()->toString();

        openssl_sign($content, $signature, $pkey);

        return base64_encode($signature);
    }
}
