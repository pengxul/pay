<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat;

use Pengxul\Pay\Plugin\Wechat\StartPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class StartPluginTest extends TestCase
{
    protected StartPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new StartPlugin();
    }

    public function testNormal()
    {
        $params = [
            'name' => 'yansongda',
            '_aaa' => 'aaa',
        ];

        $rocket = (new Rocket())->setParams($params);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $payload = $result->getPayload()->all();

        self::assertEquals('yansongda', $payload['name']);
        self::assertArrayNotHasKey('aaa', $payload);
        self::assertArrayHasKey('_aaa', $payload);
    }
}
