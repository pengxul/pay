<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Fund\Royalty\Relation;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Alipay\V2\Fund\Royalty\Relation\UnbindPlugin;
use Pengxul\Pay\Tests\TestCase;

class UnbindPluginTest extends TestCase
{
    protected UnbindPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new UnbindPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload()->toJson();

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.royalty.relation.unbind', $payload);
    }
}
