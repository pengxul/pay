<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Pay\Authorization\Pay;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Authorization\Pay\SyncPlugin;
use Pengxul\Pay\Tests\TestCase;

class SyncPluginTest extends TestCase
{
    protected SyncPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new SyncPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.orderinfo.sync', $result->getPayload()->toJson());
    }
}
