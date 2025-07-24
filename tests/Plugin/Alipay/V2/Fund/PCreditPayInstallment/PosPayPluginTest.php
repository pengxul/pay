<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Fund\PCreditPayInstallment;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Fund\PCreditPayInstallment\PosPayPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class PosPayPluginTest extends TestCase
{
    protected PosPayPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PosPayPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload()->toJson();

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.pay', $payload);
        self::assertStringContainsString('FACE_TO_FACE_PAYMENT', $payload);
        self::assertStringContainsString('bar_code', $payload);
    }
}
