<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Marketing\Transfer;

use Pengxul\Artful\Direction\OriginResponseDirection;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\Transfer\DownloadReceiptPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class DownloadReceiptPluginTest extends TestCase
{
    protected DownloadReceiptPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new DownloadReceiptPlugin();
    }

    public function testModeWrong()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider']);

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_PLUGIN_ONLY_SUPPORT_NORMAL_MODE);
        self::expectExceptionMessage('参数异常: 下载电子回单，只支持普通商户模式，当前配置为服务商模式');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
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
        ], $result->getPayload()->all());
    }
}
