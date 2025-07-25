<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Marketing\Fapiao\Blockchain;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\Fapiao\Blockchain\GetDownloadInfoPlugin;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class GetDownloadInfoPluginTest extends TestCase
{
    protected GetDownloadInfoPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new GetDownloadInfoPlugin();
    }

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: 查询电子发票，参数缺少 `fapiao_apply_id`');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalParams()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "fapiao_id" => "yansongda",
            'fapiao_apply_id' => '111',
            '_t' => 'a',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'v3/new-tax-control-fapiao/fapiao-applications/111/fapiao-files?fapiao_id=yansongda',
        ], $result->getPayload()->all());
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            'fapiao_apply_id' => '111',
            '_t' => 'a',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'v3/new-tax-control-fapiao/fapiao-applications/111/fapiao-files',
        ], $result->getPayload()->all());
    }
}
