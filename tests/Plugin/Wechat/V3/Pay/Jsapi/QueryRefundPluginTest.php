<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Pay\Jsapi;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Jsapi\QueryRefundPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class QueryRefundPluginTest extends TestCase
{
    protected QueryRefundPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryRefundPlugin();
    }

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: Jsapi 查询退款订单，参数缺少 `out_refund_no`');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "out_refund_no" => "111",
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('GET', $result->getPayload()->get('_method'));
        self::assertEquals('v3/refund/domestic/refunds/111', $result->getPayload()->get('_url'));
    }

    public function testServiceParams()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "out_refund_no" => "111",
            'sub_mchid' => '333',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('GET', $result->getPayload()->get('_method'));
        self::assertEquals('v3/refund/domestic/refunds/111?sub_mchid=333', $result->getPayload()->get('_service_url'));
    }

    public function testService()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection( [
            "out_refund_no" => "111",
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('GET', $result->getPayload()->get('_method'));
        self::assertEquals('v3/refund/domestic/refunds/111?sub_mchid=1600314070', $result->getPayload()->get('_service_url'));
    }
}
