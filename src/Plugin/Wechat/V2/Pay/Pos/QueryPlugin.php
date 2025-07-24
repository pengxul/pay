<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V2\Pay\Pos;

use Closure;
use Throwable;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Packer\XmlPacker;
use Pengxul\Artful\Rocket;
use Pengxul\Supports\Str;

use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\get_wechat_type_key;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=9_02
 */
class QueryPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws Throwable                随机字符串生成失败
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V2][Pay][Pos][QueryPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('wechat', $params);

        $rocket->setPacker(XmlPacker::class)
            ->mergePayload([
                '_url' => 'pay/orderquery',
                '_content_type' => 'application/xml',
                'appid' => $config[get_wechat_type_key($params)] ?? '',
                'mch_id' => $config['mch_id'] ?? '',
                'nonce_str' => Str::random(32),
                'sign_type' => 'MD5',
            ]);

        Logger::info('[Wechat][V2][Pay][Pos][QueryPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
