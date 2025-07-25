<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Extend\ProfitSharing;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Pay;

use function Pengxul\Pay\get_provider_config;

/**
 * @see https://pay.weixin.qq.com/docs/partner/apis/profit-sharing/merchants/query-merchant-ratio.html
 */
class QueryMerchantConfigsPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][Extend][ProfitSharing][QueryMerchantConfigsPlugin] 插件开始装载', ['rocket' => $rocket]);

        $payload = $rocket->getPayload();
        $config = get_provider_config('wechat', $rocket->getParams());
        $subMchId = $payload?->get('sub_mch_id') ?? $config['sub_mch_id'] ?? 'null';

        if (Pay::MODE_NORMAL === ($config['mode'] ?? Pay::MODE_NORMAL)) {
            throw new InvalidParamsException(Exception::PARAMS_PLUGIN_ONLY_SUPPORT_SERVICE_MODE, '参数异常: 查询最大分账比例，只支持服务商模式，当前配置为普通商户模式');
        }

        $rocket->setPayload([
            '_method' => 'GET',
            '_service_url' => 'v3/profitsharing/merchant-configs/'.$subMchId,
        ]);

        Logger::info('[Wechat][Extend][ProfitSharing][QueryMerchantConfigsPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
