<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Member;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Member\DetailPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class DetailPluginTest extends TestCase
{
    protected DetailPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new DetailPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams(['auth_token' => 'auth_token_value']);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.user.info.share', $result->getPayload()->toJson());
        self::assertStringContainsString('auth_token', $result->getPayload()->toJson());
        self::assertStringContainsString('auth_token_value', $result->getPayload()->toJson());
    }
}
