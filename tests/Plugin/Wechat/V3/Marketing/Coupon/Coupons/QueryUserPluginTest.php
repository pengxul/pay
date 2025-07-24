<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Marketing\Coupon\Coupons;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\Coupon\Coupons\QueryUserPlugin;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class QueryUserPluginTest extends TestCase
{
    protected QueryUserPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryUserPlugin();
    }

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: 根据商户号查用户的券，参数缺少 `openid`');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalParams()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "openid" => "111",
            'appid' => '222',
            '_t' => 'a',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'v3/marketing/favor/users/111/coupons?appid=222',
            '_service_url' => 'v3/marketing/favor/users/111/coupons?appid=222',
        ], $result->getPayload()->all());
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "openid" => "111",
            '_t' => 'a',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'v3/marketing/favor/users/111/coupons?appid=wx55955316af4ef13',
            '_service_url' => 'v3/marketing/favor/users/111/coupons?appid=wx55955316af4ef13',
        ], $result->getPayload()->all());
    }
}
