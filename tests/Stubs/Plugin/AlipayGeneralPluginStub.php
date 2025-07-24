<?php

namespace Pengxul\Pay\Tests\Stubs\Plugin;

use Pengxul\Pay\Plugin\Alipay\GeneralPlugin;

class AlipayGeneralPluginStub extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'yansongda';
    }
}
