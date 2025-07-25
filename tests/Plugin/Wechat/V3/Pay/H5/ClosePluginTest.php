<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Pay\H5;

use Pengxul\Artful\Direction\OriginResponseDirection;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\H5\ClosePlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class ClosePluginTest extends TestCase
{
    protected ClosePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ClosePlugin();
    }

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: H5 关闭订单，参数缺少 `out_trade_no`');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "out_trade_no" => "111",
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/pay/transactions/out-trade-no/111/close',
            '_service_url' => 'v3/pay/partner/transactions/out-trade-no/111/close',
            'mchid' => '1600314069',
        ], $result->getPayload()->all());
        self::assertEquals(OriginResponseDirection::class, $result->getDirection());
    }

    public function testServiceParams()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection( [
            "out_trade_no" => "111",
            'sub_mchid' => '333',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/pay/transactions/out-trade-no/111/close',
            '_service_url' => 'v3/pay/partner/transactions/out-trade-no/111/close',
            'sp_mchid' => '1600314069',
            'sub_mchid' => '333',
        ], $result->getPayload()->all());
        self::assertEquals(OriginResponseDirection::class, $result->getDirection());
    }

    public function testService()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection( [
            "out_trade_no" => "111",
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/pay/transactions/out-trade-no/111/close',
            '_service_url' => 'v3/pay/partner/transactions/out-trade-no/111/close',
            'sp_mchid' => '1600314069',
            'sub_mchid' => '1600314070',
        ], $result->getPayload()->all());
        self::assertEquals(OriginResponseDirection::class, $result->getDirection());
    }
}
