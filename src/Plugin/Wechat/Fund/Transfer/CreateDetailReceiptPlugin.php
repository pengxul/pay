<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\Fund\Transfer;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\GeneralPlugin;
use Pengxul\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter4_3_9.shtml
 */
class CreateDetailReceiptPlugin extends GeneralPlugin
{
    /**
     * @throws InvalidParamsException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('out_detail_no') || !$payload->has('accept_type')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/transfer-detail/electronic-receipts';
    }
}
