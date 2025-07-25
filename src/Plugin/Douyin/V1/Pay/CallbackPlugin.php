<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Douyin\V1\Pay;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Direction\NoHttpRequestDirection;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidSignException;

use function Pengxul\Artful\filter_params;
use function Pengxul\Pay\get_provider_config;

class CallbackPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Douyin][V1][Pay][CallbackPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('douyin', $params);

        $value = filter_params($params, fn ($k, $v) => '' !== $v && 'msg_signature' != $k && 'type' != $k);

        $this->verifySign($config, $value->all(), $params['msg_signature'] ?? '');

        $rocket->setPayload($params)
            ->setDirection(NoHttpRequestDirection::class)
            ->setDestination($rocket->getPayload());

        Logger::info('[Douyin][V1][Pay][CallbackPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws InvalidConfigException
     * @throws InvalidSignException
     */
    protected function verifySign(array $config, array $contents, string $sign): void
    {
        if (empty($sign)) {
            throw new InvalidSignException(Exception::SIGN_EMPTY, '签名异常: 验证抖音签名失败-抖音签名为空', func_get_args());
        }

        $contents['token'] = $config['mch_secret_token'] ?? null;

        if (empty($contents['token'])) {
            throw new InvalidConfigException(Exception::CONFIG_DOUYIN_INVALID, '配置异常: 缺少抖音配置 -- [mch_secret_token]');
        }

        sort($contents, SORT_STRING);
        $data = trim(implode('', $contents));

        $result = $sign === sha1($data);

        if (!$result) {
            throw new InvalidSignException(Exception::SIGN_ERROR, '签名异常: 验证抖音签名失败', func_get_args());
        }
    }
}
