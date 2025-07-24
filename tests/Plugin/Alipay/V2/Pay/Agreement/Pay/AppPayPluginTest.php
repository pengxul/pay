<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Pay\Agreement\Pay;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Agreement\Pay\AppPayPlugin;
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
        self::assertStringContainsString('alipay.trade.app.pay', $result->getPayload()->toJson());
    }
}
