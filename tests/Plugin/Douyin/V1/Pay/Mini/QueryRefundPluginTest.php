<?php

namespace Plugin\Douyin\V1\Pay\Mini;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\Mini\QueryRefundPlugin;
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
        self::expectExceptionMessage('参数异常: 抖音小程序查询退款订单，参数为空');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "out_order_no" => "yansongda",
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            "out_order_no" => "yansongda",
            '_method' => 'POST',
            '_url' => 'api/apps/ecpay/v1/query_refund',
            'app_id' => 'tt226e54d3bd581bf801',
        ], $result->getPayload()->all());
    }

    public function testServiceParams()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection([
            'out_order_no' => 'yansongda',
            'thirdparty_id' => 'service_provider111',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            'out_order_no' => 'yansongda',
            '_method' => 'POST',
            '_url' => 'api/apps/ecpay/v1/query_refund',
            'app_id' => 'tt226e54d3bd581bf801',
            'thirdparty_id' => 'service_provider111'
        ], $result->getPayload()->all());
    }

    public function testService()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection([
            'out_order_no' => 'yansongda',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            'out_order_no' => 'yansongda',
            '_method' => 'POST',
            '_url' => 'api/apps/ecpay/v1/query_refund',
            'app_id' => 'tt226e54d3bd581bf801',
            'thirdparty_id' => 'service_provider'
        ], $result->getPayload()->all());
    }
}
