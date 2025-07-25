<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Extend\Complaints;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\V3\Extend\Complaints\QueryImagePlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class QueryImagePluginTest extends TestCase
{
    protected QueryImagePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryImagePlugin();
    }

    public function testEmptyMediaId()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: 图片请求接口，参数缺少 `media_id`');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormal()
    {
        $payload = [
            "media_id" => "yansongda",
        ];

        $rocket = new Rocket();
        $rocket->setPayload(new Collection($payload));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'v3/merchant-service/images/yansongda',
            '_service_url' => 'v3/merchant-service/images/yansongda',
        ], $result->getPayload()->all());
    }
}
