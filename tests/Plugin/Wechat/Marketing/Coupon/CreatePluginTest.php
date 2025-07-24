<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\Marketing\Coupon;

use GuzzleHttp\Psr7\Uri;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Wechat\Marketing\Coupon\CreatePlugin;
use Pengxul\Pay\Provider\Wechat;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class CreatePluginTest extends TestCase
{
    protected CreatePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new CreatePlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())->setParams([])->setPayload(new Collection([
            'stock_name' => '7890',
            'available_begin_time' => '2020-01-01T00:00:00+08:00',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/marketing/favor/coupon-stocks'), $radar->getUri());
        self::assertEquals([
            'stock_name' => '7890',
            'available_begin_time' => '2020-01-01T00:00:00+08:00',
            'belong_merchant' => '1600314069',
        ], $result->getPayload()->all());
    }
}
