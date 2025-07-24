<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Marketing\Fapiao;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\Fapiao\QueryUserTitlePlugin;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class QueryUserTitlePluginTest extends TestCase
{
    protected QueryUserTitlePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryUserTitlePlugin();
    }

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: 获取用户填写的抬头，缺少必要参数');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalParams()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "test" => "yansongda",
            'appid' => '1111',
            '_t' => 'a',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'v3/new-tax-control-fapiao/user-title?test=yansongda&appid=1111'
        ], $result->getPayload()->all());
    }
}
