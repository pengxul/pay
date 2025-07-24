<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Marketing\Fapiao\Blockchain;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\DecryptException;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\encrypt_wechat_contents;
use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\get_wechat_public_key;
use function Pengxul\Pay\get_wechat_serial_no;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/fapiao/fapiao-applications/issue-fapiao-applications.html
 */
class CreatePlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws DecryptException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V3][Marketing][Fapiao][Blockchain][CreatePlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $payload = $rocket->getPayload();
        $config = get_provider_config('wechat', $params);

        $rocket->mergePayload(array_merge([
            '_method' => 'POST',
            '_url' => 'v3/new-tax-control-fapiao/fapiao-applications',
        ], $this->encryptSensitiveData($payload, $params, $config)));

        Logger::info('[Wechat][V3][Marketing][Fapiao][Blockchain][CreatePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     * @throws DecryptException
     */
    protected function encryptSensitiveData(?Collection $payload, array $params, array $config): array
    {
        $data['_serial_no'] = get_wechat_serial_no($params);

        $config = get_provider_config('wechat', $params);
        $publicKey = get_wechat_public_key($config, $data['_serial_no']);

        $phone = $payload?->get('buyer_information.phone') ?? null;
        $email = $payload?->get('buyer_information.email') ?? null;

        if (!is_null($phone)) {
            $data['buyer_information']['phone'] = encrypt_wechat_contents($phone, $publicKey);
        }

        if (!is_null($email)) {
            $data['buyer_information']['email'] = encrypt_wechat_contents($email, $publicKey);
        }

        return $data;
    }
}
