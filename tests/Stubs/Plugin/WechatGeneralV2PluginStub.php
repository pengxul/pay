<?php

namespace Pengxul\Pay\Tests\Stubs\Plugin;

use Pengxul\Pay\Plugin\Wechat\GeneralV2Plugin;
use Pengxul\Pay\Rocket;

class WechatGeneralV2PluginStub extends GeneralV2Plugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'yansongda/pay';
    }
}
