<?php

namespace Pengxul\Pay\Tests\Stubs\Plugin;

use Pengxul\Pay\Plugin\Unipay\GeneralPlugin;
use Pengxul\Pay\Rocket;

class UnipayGeneralPluginStub extends GeneralPlugin
{
    protected function doSomething(Rocket $rocket): void
    {
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'yansongda/pay';
    }
}

class UnipayGeneralPluginStub1 extends GeneralPlugin
{
    protected function doSomething(Rocket $rocket): void
    {
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'https://yansongda.cn/pay';
    }
}
