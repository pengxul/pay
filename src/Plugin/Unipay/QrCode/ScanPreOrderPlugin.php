<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Unipay\QrCode;

use Pengxul\Pay\Plugin\Unipay\GeneralPlugin;
use Pengxul\Pay\Rocket;

/**
 * @see https://open.unionpay.com/tjweb/acproduct/APIList?acpAPIId=795&apiservId=468&version=V2.2&bussType=0
 */
class ScanPreOrderPlugin extends GeneralPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'gateway/api/order.do';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->mergePayload([
            'bizType' => '000000',
            'txnType' => '01',
            'txnSubType' => '01',
            'channelType' => '08',
        ]);
    }
}
