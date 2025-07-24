<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Pay\Agreement\Sign;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Agreement\Sign\SignPlugin;
use Pengxul\Pay\Tests\TestCase;

class SignPluginTest extends TestCase
{
    protected SignPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new SignPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.user.agreement.page.sign', $result->getPayload()->toJson());
    }
}
