<?php

namespace Pengxul\Pay\Gateways\Alipay;

use Pengxul\Pay\Events;
use Pengxul\Pay\Exceptions\GatewayException;
use Pengxul\Pay\Exceptions\InvalidArgumentException;
use Pengxul\Pay\Exceptions\InvalidConfigException;
use Pengxul\Pay\Exceptions\InvalidSignException;
use Pengxul\Pay\Gateways\Alipay;
use Yansongda\Supports\Collection;

class PosGateway extends Gateway
{
    /**
     * Pay an order.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param string $endpoint
     *
     * @throws InvalidArgumentException
     * @throws GatewayException
     * @throws InvalidConfigException
     * @throws InvalidSignException
     */
    public function pay($endpoint, array $payload): Collection
    {
        $payload['method'] = 'alipay.trade.pay';
        $biz_array = json_decode($payload['biz_content'], true);
        if ((Alipay::MODE_SERVICE === $this->mode) && (!empty(Support::getInstance()->pid))) {
            $biz_array['extend_params'] = is_array($biz_array['extend_params']) ? array_merge(['sys_service_provider_id' => Support::getInstance()->pid], $biz_array['extend_params']) : ['sys_service_provider_id' => Support::getInstance()->pid];
        }
        $payload['biz_content'] = json_encode(array_merge(
            $biz_array,
            [
                'product_code' => 'FACE_TO_FACE_PAYMENT',
                'scene' => 'bar_code',
            ]
        ));
        $payload['sign'] = Support::generateSign($payload);

        Events::dispatch(new Events\PayStarted('Alipay', 'Pos', $endpoint, $payload));

        return Support::requestApi($payload);
    }
}
