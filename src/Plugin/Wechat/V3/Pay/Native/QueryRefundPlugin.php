<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Pay\Native;

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
 * @see https://pay.weixin.qq.com/docs/merchant/apis/native-payment/query-by-out-refund-no.html
 * @see https://pay.weixin.qq.com/docs/partner/apis/partner-native-payment/query-by-out-refund-no.html
 */
class QueryRefundPlugin implements PluginInterface
{
    /**
     * @throws InvalidParamsException
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V3][Pay][Native][QueryRefundPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('wechat', $params);
        $payload = $rocket->getPayload();
        $outRefundNo = $payload?->get('out_refund_no') ?? null;

        if (empty($outRefundNo)) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: Native 查询退款订单，参数缺少 `out_refund_no`');
        }

        $subMchId = $payload->get('sub_mchid', $config['sub_mch_id'] ?? '');

        $rocket->setPayload([
            '_method' => 'GET',
            '_url' => 'v3/refund/domestic/refunds/'.$outRefundNo,
            '_service_url' => 'v3/refund/domestic/refunds/'.$outRefundNo.'?sub_mchid='.$subMchId,
        ]);

        Logger::info('[Wechat][Pay][Native][QueryRefundPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
