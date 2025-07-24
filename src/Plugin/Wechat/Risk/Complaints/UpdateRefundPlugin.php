<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\Risk\Complaints;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\GeneralPlugin;
use Pengxul\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter10_2_19.shtml
 */
class UpdateRefundPlugin extends GeneralPlugin
{
    protected function doSomething(Rocket $rocket): void
    {
        $rocket->getPayload()->forget('complaint_id');
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('complaint_id')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/merchant-service/complaints-v2/'.
            $payload->get('complaint_id').
            '/update-refund-progress';
    }
}
