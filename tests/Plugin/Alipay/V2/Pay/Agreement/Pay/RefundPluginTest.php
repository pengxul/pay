<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Pay\Agreement\Pay;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Agreement\Pay\RefundPlugin;
use Pengxul\Pay\Tests\TestCase;

class RefundPluginTest extends TestCase
{
    protected RefundPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new RefundPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.refund', $result->getPayload()->toJson());
    }
}
