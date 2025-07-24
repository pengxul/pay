<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\Pay\Mini;

use Pengxul\Pay\Plugin\Wechat\Pay\Mini\InvokePrepayPlugin;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;
use function Pengxul\Pay\get_wechat_config;

class InvokePrepayPluginTest extends TestCase
{
    protected InvokePrepayPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new InvokePrepayPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())->setParams([])->setDestination(new Collection(['prepay_id' => 'yansongda anthony']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();
        $config = get_wechat_config($rocket->getParams());

        self::assertArrayHasKey('appId', $contents->all());
        self::assertEquals($config['mini_app_id'], $contents->get('appId'));
        self::assertArrayHasKey('nonceStr', $contents->all());
        self::assertArrayHasKey('package', $contents->all());
        self::assertArrayHasKey('signType', $contents->all());
        self::assertArrayHasKey('paySign', $contents->all());
    }

    public function testPartnerSpAppId()
    {
        $rocket = (new Rocket())->setParams(['_config' => 'service_provider']);
        $rocket->setPayload(new Collection(['out_trade_no'=>'121218']));
        $rocket->setDestination(new Collection(['prepay_id' => 'yansongda anthony']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();
        $config = get_wechat_config($rocket->getParams());

        self::assertArrayHasKey('appId', $contents->all());
        self::assertEquals($config['mini_app_id'], $contents->get('appId'));
        self::assertArrayHasKey('nonceStr', $contents->all());
        self::assertArrayHasKey('package', $contents->all());
        self::assertArrayHasKey('signType', $contents->all());
        self::assertArrayHasKey('paySign', $contents->all());
    }

    public function testPartnerSubAppId()
    {
        $rocket = (new Rocket())->setParams(['_config' => 'service_provider']);
        $rocket->setPayload(new Collection(['out_trade_no'=>'121218','sub_appid' =>'wx55955316af4ef88']));
        $rocket->setDestination(new Collection(['prepay_id' => 'yansongda anthony']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();

        self::assertArrayHasKey('appId', $contents->all());
        self::assertEquals('wx55955316af4ef88', $contents->get('appId'));
        self::assertArrayHasKey('nonceStr', $contents->all());
        self::assertArrayHasKey('package', $contents->all());
        self::assertArrayHasKey('signType', $contents->all());
        self::assertArrayHasKey('paySign', $contents->all());
    }
}
