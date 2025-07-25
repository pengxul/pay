<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Marketing\Fapiao\Blockchain;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\Fapiao\Blockchain\GetTaxCodePlugin;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class GetTaxCodePluginTest extends TestCase
{
    protected GetTaxCodePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new GetTaxCodePlugin();
    }

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: 获取商户可开具的商品和服务税收分类编码对照表，缺少 `offset` 或 `limit` 参数');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "offset" => "yansongda",
            'limit' => '111',
            '_t' => 'a',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'v3/new-tax-control-fapiao/merchant/tax-codes?offset=yansongda&limit=111',
        ], $result->getPayload()->all());
    }
}
