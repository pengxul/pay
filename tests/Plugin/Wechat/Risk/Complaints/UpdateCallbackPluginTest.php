<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\Risk\Complaints;

use GuzzleHttp\Psr7\Uri;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Wechat\Risk\Complaints\UpdateCallbackPlugin;
use Pengxul\Pay\Provider\Wechat;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class UpdateCallbackPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Pay\Plugin\Wechat\Risk\Complaints\UpdateCallbackPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new UpdateCallbackPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['url' => 'bar']));

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket;});

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/merchant-service/complaint-notifications'), $radar->getUri());
        self::assertEquals(['url' => 'bar'], $rocket->getPayload()->toArray());
        self::assertEquals('PUT', $radar->getMethod());
    }
}
