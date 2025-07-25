<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Fund\Royalty\Query;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Alipay\V2\Fund\Royalty\Query\OnsettlePlugin;
use Pengxul\Pay\Tests\TestCase;

class OnsettlePluginTest extends TestCase
{
    protected OnsettlePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new OnsettlePlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload()->toJson();

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.order.onsettle.query', $payload);
    }
}
