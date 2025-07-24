<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Unipay\QrCode;

use Pengxul\Pay\Plugin\Unipay\GeneralPlugin;
use Pengxul\Pay\Rocket;

/**
 * @see https://open.unionpay.com/tjweb/acproduct/APIList?acpAPIId=798&apiservId=468&version=V2.2&bussType=0
 */
class PosNormalPlugin extends GeneralPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'gateway/api/backTransReq.do';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->mergePayload([
            'bizType' => '000000',
            'txnType' => '01',
            'txnSubType' => '06',
            'channelType' => '08',
        ]);
    }
}
