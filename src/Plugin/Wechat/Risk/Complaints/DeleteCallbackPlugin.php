<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\Risk\Complaints;

use Pengxul\Pay\Direction\OriginResponseDirection;
use Pengxul\Pay\Plugin\Wechat\GeneralPlugin;
use Pengxul\Pay\Rocket;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter10_2_5.shtml
 */
class DeleteCallbackPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'DELETE';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setDirection(OriginResponseDirection::class);

        $rocket->setPayload(null);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/merchant-service/complaint-notifications';
    }
}
