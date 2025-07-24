<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Pay\Agreement\Sign;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Agreement\Sign\UnsignPlugin;
use Pengxul\Pay\Tests\TestCase;

class UnsignPluginTest extends TestCase
{
    protected UnsignPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new UnsignPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.user.agreement.unsign', $result->getPayload()->toJson());
    }
}
