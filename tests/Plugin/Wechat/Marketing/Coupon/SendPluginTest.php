<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\Marketing\Coupon;

use GuzzleHttp\Psr7\Uri;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Wechat\Marketing\Coupon\SendPlugin;
use Pengxul\Pay\Provider\Wechat;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class SendPluginTest extends TestCase
{
    protected SendPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new SendPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())->setParams([])->setPayload(new Collection([
            'openid' => '7890',
            'stock_id' => '123',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/marketing/favor/users/7890/coupons'), $radar->getUri());
        self::assertEquals([
            'stock_id' => '123',
            'appid' => 'wx55955316af4ef13',
            'stock_creator_mchid' => '1600314069',
        ], $result->getPayload()->all());
    }

    public function testException()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $rocket = (new Rocket())->setParams([])->setPayload(new Collection());

        $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });
    }

    public function testExistMchId()
    {
        $rocket = (new Rocket())->setParams([])->setPayload(new Collection([
            'openid' => '7890',
            'stock_id' => '123',
            'stock_creator_mchid' => '123',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/marketing/favor/users/7890/coupons'), $radar->getUri());
        self::assertEquals([
            'stock_id' => '123',
            'appid' => 'wx55955316af4ef13',
            'stock_creator_mchid' => '123',
        ], $result->getPayload()->all());
    }

    public function testOtherAppId()
    {
        $rocket = (new Rocket())->setParams(['_type' => 'mini'])->setPayload(new Collection([
            'openid' => '7890',
            'stock_id' => '123',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/marketing/favor/users/7890/coupons'), $radar->getUri());
        self::assertEquals([
            'stock_id' => '123',
            'appid' => 'wx55955316af4ef14',
            'stock_creator_mchid' => '1600314069',
        ], $result->getPayload()->all());
    }
}
