<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Marketing\Fapiao\Blockchain;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\Fapiao\Blockchain\DownloadPlugin;
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
        self::expectExceptionMessage('参数异常: 下载发票文件，缺少 `download_url` 参数');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalParams()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "download_url" => "https://pay.yansongda.cn?token=123",
            'appid' => '1111',
            '_t' => 'a',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'https://pay.yansongda.cn?token=123&appid=1111',
        ], $result->getPayload()->all());
    }
}
