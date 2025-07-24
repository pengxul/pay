<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\Pay\Common;

use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Exception\ServiceNotFoundException;
use Pengxul\Pay\Plugin\Wechat\GeneralPlugin;
use Pengxul\Pay\Rocket;

use function Pengxul\Pay\get_wechat_config;

class QueryRefundPlugin extends GeneralPlugin
{
    /**
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('out_refund_no')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/refund/domestic/refunds/'.$payload->get('out_refund_no');
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function getPartnerUri(Rocket $rocket): string
    {
        $config = get_wechat_config($rocket->getParams());
        $url = parent::getPartnerUri($rocket);

        return $url.'?sub_mchid='.$rocket->getPayload()->get('sub_mchid', $config['sub_mch_id'] ?? '');
    }

    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setPayload(null);
    }
}
