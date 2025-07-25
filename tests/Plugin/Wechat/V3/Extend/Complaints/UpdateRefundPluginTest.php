<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Extend\Complaints;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\V3\Extend\Complaints\UpdateRefundPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class UpdateRefundPluginTest extends TestCase
{
    protected UpdateRefundPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new UpdateRefundPlugin();
    }

    public function testEmptyComplaintId()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: 更新退款审批结果，参数缺少 `complaint_id`');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testPayload()
    {
        $payload = [
            "complaint_id" => "yansongda",
            'action' => 'APPROVE',
            'name' => 'yansongda',
        ];

        $rocket = new Rocket();
        $rocket->setPayload(new Collection($payload));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/merchant-service/complaints-v2/yansongda/update-refund-progress',
            '_service_url' => 'v3/merchant-service/complaints-v2/yansongda/update-refund-progress',
            'action' => 'APPROVE',
            'name' => 'yansongda',
        ], $result->getPayload()->all());
    }

}
