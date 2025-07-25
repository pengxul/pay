<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Fund\Transfer;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Fund\Transfer\ApplyReceiptPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class ApplyReceiptPluginTest extends TestCase
{
    protected ApplyReceiptPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ApplyReceiptPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload()->toJson();

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.data.bill.ereceipt.apply', $payload);
    }
}
