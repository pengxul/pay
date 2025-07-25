<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Pay\Authorization\Auth;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Authorization\Auth\AppFreezePlugin;
use Pengxul\Pay\Tests\TestCase;

class AppFreezePluginTest extends TestCase
{
    protected AppFreezePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AppFreezePlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.fund.auth.order.app.freeze', $result->getPayload()->toJson());
        self::assertStringContainsString('PREAUTH_PAY', $result->getPayload()->toJson());
    }
}
