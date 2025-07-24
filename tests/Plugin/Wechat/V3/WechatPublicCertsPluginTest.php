<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3;

use Pengxul\Pay\Plugin\Wechat\V3\WechatPublicCertsPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class WechatPublicCertsPluginTest extends TestCase
{
    protected WechatPublicCertsPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new WechatPublicCertsPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())->setParams(['aaa' => 'bbb'])->setPayload(['name' => 'yansongda']);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload()->all();

        self::assertEquals('GET', $payload['_method']);
        self::assertEquals('v3/certificates', $payload['_url']);
        self::assertArrayNotHasKey('aaa', $payload);
        self::assertArrayNotHasKey('name', $payload);
    }
}
