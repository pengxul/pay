<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Unipay\Open;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Supports\Collection;

use function Pengxul\Artful\filter_params;
use function Pengxul\Pay\get_provider_config;

class AddPayloadSignaturePlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Unipay][AddPayloadSignaturePlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('unipay', $params);
        $payload = $rocket->getPayload();

        if (empty($payload) || $payload->isEmpty()) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 银联支付必要参数缺失。可能插件用错顺序，应该先使用 `业务插件`');
        }

        $rocket->mergePayload([
            'signature' => $this->getSignature($config['certs']['pkey'] ?? '', filter_params($rocket->getPayload(), fn ($k, $v) => 'signature' != $k)),
        ]);

        Logger::info('[Unipay][AddPayloadSignaturePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getSignature(string $pkey, Collection $payload): string
    {
        if (empty($pkey)) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 银联支付配置文件中未找到 `certs.pkey` 配置项。可能插件用错顺序，应该先使用 `StartPlugin`');
        }

        $content = $payload->sortKeys()->toString();

        openssl_sign(hash('sha256', $content), $sign, $pkey, 'sha256');

        return base64_encode($sign);
    }
}
