<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Pay\Combine;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Direction\OriginResponseDirection;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;

use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\get_wechat_type_key;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/combine-payment/orders/close-order.html
 * @see https://pay.weixin.qq.com/docs/partner/apis/combine-payment/orders/close-order.html
 */
class ClosePlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][Pay][Combine][ClosePlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('wechat', $params);
        $payload = $rocket->getPayload();
        $combineOutTradeNo = $payload?->get('combine_out_trade_no') ?? null;

        if (empty($combineOutTradeNo)) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 合单关单，参数缺少 `combine_out_trade_no`');
        }

        $rocket->setDirection(OriginResponseDirection::class)
            ->mergePayload([
                '_method' => 'POST',
                '_url' => 'v3/combine-transactions/out-trade-no/'.$combineOutTradeNo.'/close',
                '_service_url' => 'v3/combine-transactions/out-trade-no/'.$combineOutTradeNo.'/close',
                'combine_appid' => $payload->get('combine_appid', $config[get_wechat_type_key($params)] ?? ''),
            ])
            ->exceptPayload('combine_out_trade_no');

        Logger::info('[Wechat][Pay][Combine][ClosePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
