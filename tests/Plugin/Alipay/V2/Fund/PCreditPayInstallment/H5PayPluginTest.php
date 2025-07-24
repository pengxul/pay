<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Fund\PCreditPayInstallment;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Fund\PCreditPayInstallment\H5PayPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class H5PayPluginTest extends TestCase
{
    protected H5PayPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new H5PayPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.wap.pay', $result->getPayload()->toJson());
        self::assertStringContainsString('QUICK_WAP_WAY', $result->getPayload()->toJson());
    }
}
