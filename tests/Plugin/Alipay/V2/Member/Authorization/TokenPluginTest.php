<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Member\Authorization;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Member\Authorization\TokenPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class TokenPluginTest extends TestCase
{
    protected TokenPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new TokenPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams(['name' => 'yansongda']);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload();

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertEquals('alipay.system.oauth.token', $payload->get('method'));
        self::assertEquals('yansongda', $payload->get('name'));
        self::assertFalse($payload->has('biz_content'));
    }
}
