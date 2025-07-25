<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Marketing\Transfer;

use Closure;
use JetBrains\PhpStorm\Deprecated;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Direction\OriginResponseDirection;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Pay;

use function Pengxul\Pay\get_provider_config;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/batch-transfer-to-balance/download-receipt.html
 */
#[Deprecated(reason: '由于微信支付变更，自 v3.7.12 开始废弃, 并将在 v3.8.0 移除')]
class DownloadReceiptPlugin implements PluginInterface
{
    /**
     * @throws InvalidParamsException
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][Marketing][Transfer][DownloadReceiptPlugin] 插件开始装载', ['rocket' => $rocket]);

        $downloadUrl = $rocket->getPayload()?->get('download_url') ?? null;
        $config = get_provider_config('wechat', $rocket->getParams());

        if (Pay::MODE_SERVICE === ($config['mode'] ?? Pay::MODE_NORMAL)) {
            throw new InvalidParamsException(Exception::PARAMS_PLUGIN_ONLY_SUPPORT_NORMAL_MODE, '参数异常: 下载电子回单，只支持普通商户模式，当前配置为服务商模式');
        }

        if (empty($downloadUrl)) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 下载电子回单，参数缺少 `download_url`');
        }

        $rocket->setDirection(OriginResponseDirection::class)
            ->setPayload([
                '_method' => 'GET',
                '_url' => $downloadUrl,
            ]);

        Logger::info('[Wechat][Marketing][Transfer][DownloadReceiptPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
