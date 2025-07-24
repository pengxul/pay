<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay;

use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\Stubs\Plugin\AlipayGeneralPluginStub;
use Pengxul\Pay\Tests\TestCase;

class GeneralPayPluginTest extends TestCase
{
    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $plugin = new AlipayGeneralPluginStub();

        $result = $plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertStringContainsString('yansongda', $result->getPayload()->toJson());
    }
}

