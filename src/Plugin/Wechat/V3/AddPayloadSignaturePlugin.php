<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3;

use Closure;
use Throwable;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Supports\Collection;
use Pengxul\Supports\Str;

use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\get_public_cert;
use function Pengxul\Pay\get_wechat_body;
use function Pengxul\Pay\get_wechat_method;
use function Pengxul\Pay\get_wechat_sign;
use function Pengxul\Pay\get_wechat_url;

class AddPayloadSignaturePlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     * @throws Throwable                随机数生成失败
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V3][AddPayloadSignaturePlugin] 插件开始装载', ['rocket' => $rocket]);

        $config = get_provider_config('wechat', $rocket->getParams());
        $payload = $rocket->getPayload();

        $timestamp = time();
        $random = Str::random(32);
        $signContent = $this->getSignatureContent($config, $payload, $timestamp, $random);
        $signature = $this->getSignature($config, $timestamp, $random, $signContent);

        $rocket->mergePayload(['_authorization' => $signature]);

        Logger::info('[Wechat][V3][AddPayloadSignaturePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getSignatureContent(array $config, ?Collection $payload, int $timestamp, string $random): string
    {
        $url = get_wechat_url($config, $payload);
        $urlPath = parse_url($url, PHP_URL_PATH);
        $urlQuery = parse_url($url, PHP_URL_QUERY);

        return get_wechat_method($payload)."\n"
            .$urlPath.(empty($urlQuery) ? '' : '?'.$urlQuery)."\n"
            .$timestamp."\n"
            .$random."\n"
            .get_wechat_body($payload)."\n";
    }

    /**
     * @throws InvalidConfigException
     */
    protected function getSignature(array $config, int $timestamp, string $random, string $contents): string
    {
        $mchPublicCertPath = $config['mch_public_cert_path'] ?? null;

        if (empty($mchPublicCertPath)) {
            throw new InvalidConfigException(Exception::CONFIG_WECHAT_INVALID, '配置异常: 缺少微信配置 -- [mch_public_cert_path]');
        }

        $ssl = openssl_x509_parse(get_public_cert($mchPublicCertPath));

        if (empty($ssl['serialNumberHex'])) {
            throw new InvalidConfigException(Exception::CONFIG_WECHAT_INVALID, '配置异常: 解析微信配置 [mch_public_cert_path] 出错');
        }

        $auth = sprintf(
            'mchid="%s",nonce_str="%s",timestamp="%d",serial_no="%s",signature="%s"',
            $config['mch_id'] ?? '',
            $random,
            $timestamp,
            $ssl['serialNumberHex'],
            get_wechat_sign($config, $contents),
        );

        return 'WECHATPAY2-SHA256-RSA2048 '.$auth;
    }
}
