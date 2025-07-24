<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Extend\Complaints;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\V3\Extend\Complaints\QueryNegotiationPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class QueryNegotiationPluginTest extends TestCase
{
    protected QueryNegotiationPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryNegotiationPlugin();
    }

    public function testEmptyComplaintId()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: 查询投诉单协商历史，参数缺少 `complaint_id`');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testQueryEmpty()
    {
        $payload = [
            "complaint_id" => "yansongda",
            '_t' => 'a',
        ];

        $rocket = new Rocket();
        $rocket->setPayload(new Collection($payload));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'v3/merchant-service/complaints-v2/yansongda/negotiation-historys',
            '_service_url' => 'v3/merchant-service/complaints-v2/yansongda/negotiation-historys',
        ], $result->getPayload()->all());
    }

    public function testQueryNotEmpty()
    {
        $payload = [
            "complaint_id" => "yansongda",
            'limit' => 2,
            'offset' => 3,
            '_t' => 'a',
        ];

        $rocket = new Rocket();
        $rocket->setPayload(new Collection($payload));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'v3/merchant-service/complaints-v2/yansongda/negotiation-historys?limit=2&offset=3',
            '_service_url' => 'v3/merchant-service/complaints-v2/yansongda/negotiation-historys?limit=2&offset=3',
        ], $result->getPayload()->all());
    }
}
