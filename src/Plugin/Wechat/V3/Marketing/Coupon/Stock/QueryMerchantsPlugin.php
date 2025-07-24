<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Marketing\Coupon\Stock;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Supports\Collection;

use function Pengxul\Artful\filter_params;
use function Pengxul\Pay\get_provider_config;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/cash-coupons/stock/list-available-merchants.html
 * @see https://pay.weixin.qq.com/docs/partner/apis/cash-coupons/stock/list-available-merchants.html
 */
class QueryMerchantsPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V3][Marketing][Coupon][Stock][QueryMerchantsPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('wechat', $params);
        $payload = $rocket->getPayload();
        $stockId = $payload?->get('stock_id') ?? null;

        if (empty($stockId)) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 查询代金券可用商户，参数缺少 `stock_id`');
        }

        $rocket->setPayload([
            '_method' => 'GET',
            '_url' => 'v3/marketing/favor/stocks/'.$stockId.'/merchants?'.$this->normal($payload, $config),
            '_service_url' => 'v3/marketing/favor/stocks/'.$stockId.'/merchants?'.$this->normal($payload, $config),
        ]);

        Logger::info('[Wechat][V3][Marketing][Coupon][Stock][QueryMerchantsPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    public function normal(Collection $payload, array $config): string
    {
        $stockCreatorMchId = $payload->get('stock_creator_mchid');

        if (is_null($stockCreatorMchId)) {
            $payload->set('stock_creator_mchid', $config['mch_id'] ?? '');
        }

        return filter_params($payload)->except('stock_id')->query();
    }
}
