<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Pay\Combine;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Combine\H5PayPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class H5PayPluginTest extends TestCase
{
    protected H5PayPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new H5PayPlugin();
    }

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: H5合单 下单，参数为空');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalParams()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            'combine_mchid' => '333',
            'combine_appid' => 'yansongdaaa',
            'notify_url' => '444',
            'name' => 'yansongda',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/combine-transactions/h5',
            '_service_url' => 'v3/combine-transactions/h5',
            "combine_appid" => "yansongdaaa",
            'combine_mchid' => '333',
            'notify_url' => '444',
            'name' => 'yansongda',
        ], $result->getPayload()->all());
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            'name' => 'yansongda',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/combine-transactions/h5',
            '_service_url' => 'v3/combine-transactions/h5',
            "combine_appid" => "wx55955316af4ef13",
            'combine_mchid' => '1600314069',
            'notify_url' => 'https://pay.yansongda.cn',
            'name' => 'yansongda',
        ], $result->getPayload()->all());
    }
}
