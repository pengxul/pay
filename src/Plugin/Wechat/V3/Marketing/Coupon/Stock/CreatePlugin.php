<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Marketing\Coupon\Stock;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

use function Pengxul\Pay\get_provider_config;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/cash-coupons/stock/create-coupon-stock.html
 * @see https://pay.weixin.qq.com/docs/partner/apis/cash-coupons/stock/create-coupon-stock.html
 */
class CreatePlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V3][Marketing][Coupon][Stock][CreatePlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('wechat', $params);
        $belongMerchant = $rocket->getPayload()?->get('belong_merchant') ?? $config['mch_id'];

        $rocket->mergePayload([
            '_method' => 'POST',
            '_url' => 'v3/marketing/favor/coupon-stocks',
            '_service_url' => 'v3/marketing/favor/coupon-stocks',
            'belong_merchant' => $belongMerchant,
        ]);

        Logger::info('[Wechat][V3][Marketing][Coupon][Stock][CreatePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
