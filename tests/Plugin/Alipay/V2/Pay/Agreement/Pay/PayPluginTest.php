<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Pay\Agreement\Pay;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Agreement\Pay\PayPlugin;
use Pengxul\Pay\Tests\TestCase;

class PayPluginTest extends TestCase
{
    protected PayPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PayPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.pay', $result->getPayload()->toJson());
        self::assertStringContainsString('GENERAL_WITHHOLDING', $result->getPayload()->toJson());
    }
}
