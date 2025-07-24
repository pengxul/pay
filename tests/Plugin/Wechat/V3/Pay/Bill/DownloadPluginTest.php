<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Pay\Bill;

use Pengxul\Artful\Direction\OriginResponseDirection;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Bill\DownloadPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class DownloadPluginTest extends TestCase
{
    protected DownloadPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new DownloadPlugin();
    }

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: App 下载交易对账单，参数缺少 `download_url`');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "download_url" => "111",
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(OriginResponseDirection::class, $result->getDirection());
        self::assertEquals([
            '_method' => 'GET',
            '_url' => '111',
            '_service_url' => '111',
        ], $result->getPayload()->all());
    }
}
