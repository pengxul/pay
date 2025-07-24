<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2;

use Pengxul\Pay\Plugin\Alipay\V2\FormatPayloadBizContentPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class FormatPayloadBizContentPluginTest extends TestCase
{
    protected FormatPayloadBizContentPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new FormatPayloadBizContentPlugin();
    }

    public function testSignNormal()
    {
        $payload = [
            "biz_content" => ['out_trade_no' => "yansongda-1622986519"],
        ];

        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection($payload));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('{"out_trade_no":"yansongda-1622986519"}', $result->getPayload()->get('biz_content'));
    }

    public function testSignUnderlineParams()
    {
        $payload = [
            "biz_content" => ['out_trade_no' => "yansongda-1622986519", '_method' => 'get', '_ignore' => true],
        ];

        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection($payload));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('{"out_trade_no":"yansongda-1622986519"}', $result->getPayload()->get('biz_content'));
    }
}
