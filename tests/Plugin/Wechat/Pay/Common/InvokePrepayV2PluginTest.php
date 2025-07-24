<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\Pay\Common;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidResponseException;
use Pengxul\Pay\Plugin\Wechat\Pay\Common\InvokePrepayV2Plugin;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;
use function Pengxul\Pay\get_wechat_config;

class InvokePrepayV2PluginTest extends TestCase
{
    protected InvokePrepayV2Plugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new InvokePrepayV2Plugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())->setDestination(new Collection(['prepay_id' => 'yansongda']));
        $config = get_wechat_config($rocket->getParams());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();

        self::assertArrayHasKey('paySign', $contents->all());
        self::assertArrayHasKey('timeStamp', $contents->all());
        self::assertArrayHasKey('nonceStr', $contents->all());
        self::assertEquals('prepay_id=yansongda', $contents->get('package'));
        self::assertEquals('MD5', $contents->get('signType'));
        self::assertEquals($config['mp_app_id'], $contents->get('appId'));
    }

    public function testWrongPrepayId()
    {
        $rocket = (new Rocket())->setDestination(new Collection([]));

        self::expectException(InvalidResponseException::class);
        self::expectExceptionCode(Exception::RESPONSE_MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }
}
