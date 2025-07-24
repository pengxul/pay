<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Member\Authorization;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Member\Authorization\AuthPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class AuthPluginTest extends TestCase
{
    protected AuthPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AuthPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload()->toJson();

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.user.info.auth', $payload);
    }
}
