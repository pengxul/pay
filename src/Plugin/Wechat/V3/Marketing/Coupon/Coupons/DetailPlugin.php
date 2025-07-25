<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Marketing\Coupon\Coupons;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;

use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\get_wechat_type_key;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/cash-coupons/coupon/query-coupon.html
 * @see https://pay.weixin.qq.com/docs/partner/apis/cash-coupons/coupon/query-coupon.html
 */
class DetailPlugin implements PluginInterface
{
    /**
     * @throws InvalidParamsException
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V3][Marketing][Coupon][Coupons][DetailPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('wechat', $params);
        $payload = $rocket->getPayload();
        $openId = $payload?->get('openid') ?? null;
        $couponId = $payload?->get('coupon_id') ?? null;
        $appId = $payload?->get('appid') ?? $config[get_wechat_type_key($params)] ?? 'null';

        if (empty($openId) || empty($couponId)) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 查询代金券详情，参数缺少 `openid` 或 `coupon_id`');
        }

        $rocket->setPayload([
            '_method' => 'GET',
            '_url' => 'v3/marketing/favor/users/'.$openId.'/coupons/'.$couponId.'?appid='.$appId,
            '_service_url' => 'v3/marketing/favor/users/'.$openId.'/coupons/'.$couponId.'?appid='.$appId,
        ]);

        Logger::info('[Wechat][V3][Marketing][Coupon][Coupons][DetailPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
