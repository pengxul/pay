<?php

namespace Plugin\Wechat\V3\Extend\ProfitSharing;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Wechat\V3\Extend\ProfitSharing\GetBillPlugin;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class GetBillPluginTest extends TestCase
{
    protected GetBillPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new GetBillPlugin();
    }

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: 分账 申请账单，参数为空');

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
            '_url' => 'v3/profitsharing/bills?download_url=111',
            '_service_url' => 'v3/profitsharing/bills?download_url=111',
        ], $result->getPayload()->all());
    }
}
