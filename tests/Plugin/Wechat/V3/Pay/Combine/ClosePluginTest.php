<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Pay\Combine;

use Pengxul\Artful\Direction\OriginResponseDirection;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Combine\ClosePlugin;
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
        self::expectExceptionMessage('参数异常: 合单关单，参数缺少 `combine_out_trade_no`');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalParams()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "combine_out_trade_no" => "111",
            'combine_appid' => '333',
            'name' => 'yansongda',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/combine-transactions/out-trade-no/111/close',
            '_service_url' => 'v3/combine-transactions/out-trade-no/111/close',
            'combine_appid' => '333',
            'name' => 'yansongda',
        ], $result->getPayload()->all());
        self::assertEquals(OriginResponseDirection::class, $result->getDirection());
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "combine_out_trade_no" => "111",
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/combine-transactions/out-trade-no/111/close',
            '_service_url' => 'v3/combine-transactions/out-trade-no/111/close',
            'combine_appid' => 'wx55955316af4ef13',
        ], $result->getPayload()->all());
        self::assertEquals(OriginResponseDirection::class, $result->getDirection());
    }
}
