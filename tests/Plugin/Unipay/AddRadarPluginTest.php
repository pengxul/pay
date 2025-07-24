<?php

namespace Pengxul\Pay\Tests\Plugin\Unipay;

use Pengxul\Pay\Plugin\Unipay\AddRadarPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class AddRadarPluginTest extends TestCase
{
    protected AddRadarPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AddRadarPlugin();
    }

    public function testNormal()
    {
        $params = [];
        $payload = new Collection([
            '_url' => 'https://pay.yansongda.cn',
            '_body' => '123',
        ]);

        $rocket = (new Rocket())->setParams($params)->setPayload($payload);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $radar = $result->getRadar();

        self::assertEquals('123', (string) $radar->getBody());
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals('https://pay.yansongda.cn', (string) $radar->getUri());
    }
}
