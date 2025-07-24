<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Fund\Royalty\Request;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Alipay\V2\Fund\Royalty\Request\SettleOrderPlugin;
use Pengxul\Pay\Tests\TestCase;

class SettleOrderPluginTest extends TestCase
{
    protected SettleOrderPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new SettleOrderPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload()->toJson();

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.order.settle', $payload);
    }
}
