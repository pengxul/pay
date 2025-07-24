<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Unipay\Qra\Pos;

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

/**
 * @see https://up.95516.com/open/openapi/doc?index_1=2&index_2=1&chapter_1=274&chapter_2=295
 */
class RefundPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws Throwable                随机数生成失败
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Unipay][Qra][Pos][RefundPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('unipay', $params);

        $rocket->setPacker(XmlPacker::class)
            ->mergePayload([
                '_url' => 'https://qra.95516.com/pay/gateway',
                'service' => 'unified.trade.refund',
                'charset' => 'UTF-8',
                'sign_type' => 'MD5',
                'mch_id' => $config['mch_id'] ?? '',
                'nonce_str' => Str::random(32),
            ]);

        Logger::info('[Unipay][Qra][Pos][RefundPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
