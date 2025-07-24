<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Marketing\Coupon\Coupons;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\Coupon\Coupons\SendPlugin;
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

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: 发放指定批次的代金券，参数缺少 `openid`');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalParams()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection([
            'openid' => '111',
            "appid" => "222",
            'stock_creator_mchid' => '333',
            'test' => 'yansongda'
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/marketing/favor/users/111/coupons',
            '_service_url' => 'v3/marketing/favor/users/111/coupons',
            'appid' => '222',
            'stock_creator_mchid' => '333',
            'test' => 'yansongda',
        ], $result->getPayload()->all());
    }
    
    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection([
            'openid' => '111',
            "test" => "yansongda",
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/marketing/favor/users/111/coupons',
            '_service_url' => 'v3/marketing/favor/users/111/coupons',
            'appid' => 'wx55955316af4ef13',
            'stock_creator_mchid' => '1600314069',
            'test' => 'yansongda',
        ], $result->getPayload()->all());
    }
}
