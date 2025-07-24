<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2;

use Pengxul\Pay\Plugin\Alipay\V2\AddRadarPlugin;
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

    public function testRadarPostNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['name' => 'yansongda']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('https://openapi.alipay.com/gateway.do?charset=utf-8', (string) $result->getRadar()->getUri());
        self::stringContains('name=yansongda', (string) $result->getRadar()->getBody());
        self::assertEquals('POST', $result->getRadar()->getMethod());
    }

    public function testRadarGetNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_method' => 'get'])->setPayload(new Collection(['name' => 'yansongda']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('https://openapi.alipay.com/gateway.do?charset=utf-8', (string) $result->getRadar()->getUri());
        self::assertEquals('GET', $result->getRadar()->getMethod());
    }
}
