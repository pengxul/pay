<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Fund\PCreditPayInstallment;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Fund\PCreditPayInstallment\ScanPayPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class ScanPayPluginTest extends TestCase
{
    protected ScanPayPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ScanPayPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload()->toJson();

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.precreate', $payload);
    }
}
