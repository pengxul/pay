<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Marketing\Redpack;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Marketing\Redpack\AppPayPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class AppPayPluginTest extends TestCase
{
    protected AppPayPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AppPayPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.fund.trans.app.pay', $result->getPayload()->toJson());
        self::assertStringContainsString('STD_RED_PACKET', $result->getPayload()->toJson());
        self::assertStringContainsString('PERSONAL_PAY', $result->getPayload()->toJson());
    }
}
