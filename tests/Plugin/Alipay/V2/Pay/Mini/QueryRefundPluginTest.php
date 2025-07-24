<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Pay\Mini;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Mini\QueryRefundPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class QueryRefundPluginTest extends TestCase
{
    protected QueryRefundPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryRefundPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.trade.fastpay.refund.query', $result->getPayload()->toJson());
    }
}
