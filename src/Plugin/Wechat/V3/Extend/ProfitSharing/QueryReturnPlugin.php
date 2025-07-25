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

use function Pengxul\Pay\get_provider_config;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/profit-sharing/return-orders/query-return-order.html
 * @see https://pay.weixin.qq.com/docs/partner/apis/profit-sharing/return-orders/query-return-order.html
 */
class QueryReturnPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][Extend][ProfitSharing][QueryReturnPlugin] 插件开始装载', ['rocket' => $rocket]);

        $config = get_provider_config('wechat', $rocket->getParams());
        $payload = $rocket->getPayload();
        $outOrderNo = $payload?->get('out_order_no') ?? null;
        $outReturnNo = $payload?->get('out_return_no') ?? null;
        $subMchId = $payload?->get('sub_mchid') ?? $config['sub_mch_id'] ?? 'null';

        if (empty($outOrderNo) || empty($outReturnNo)) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 查询分账结果, 缺少必要参数 `out_order_no`, `out_return_no`');
        }

        $rocket->setPayload([
            '_method' => 'GET',
            '_url' => 'v3/profitsharing/return-orders/'.$outReturnNo.'?out_order_no='.$outOrderNo,
            '_service_url' => 'v3/profitsharing/return-orders/'.$outReturnNo.'?sub_mchid='.$subMchId.'&out_order_no='.$outOrderNo,
        ]);

        Logger::info('[Wechat][Extend][ProfitSharing][QueryReturnPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
