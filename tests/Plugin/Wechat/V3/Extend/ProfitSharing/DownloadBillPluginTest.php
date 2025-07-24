<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Extend\ProfitSharing;

use Pengxul\Artful\Direction\OriginResponseDirection;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\V3\Extend\ProfitSharing\DownloadBillPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class DownloadBillPluginTest extends TestCase
{
    protected DownloadBillPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new DownloadBillPlugin();
    }

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: 下载电子回单，参数缺少 `download_url`');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "download_url" => "yansongda",
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(OriginResponseDirection::class, $result->getDirection());
        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'yansongda',
            '_service_url' => 'yansongda',
        ], $result->getPayload()->all());
    }
}
