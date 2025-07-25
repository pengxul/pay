<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Pay\App;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\App\GetFundBillPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class GetFundBillPluginTest extends TestCase
{
    protected GetFundBillPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new GetFundBillPlugin();
    }

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: App 申请资金账单，参数为空');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "download_url" => "111",
            '_t' => 'a',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'v3/bill/fundflowbill?download_url=111',
            '_service_url' => 'v3/bill/fundflowbill?download_url=111',
        ], $result->getPayload()->all());
    }
}
