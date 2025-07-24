<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Pay\Mini;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Mini\ClosePlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class ClosePluginTest extends TestCase
{
    protected ClosePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ClosePlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.close', $result->getPayload()->toJson());
    }
}
