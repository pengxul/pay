<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Marketing\Redpack;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Marketing\Redpack\WebPayPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class WebPayPluginTest extends TestCase
{
    protected WebPayPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new WebPayPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.fund.trans.page.pay', $result->getPayload()->toJson());
        self::assertStringContainsString('STD_APP_TRANSFER', $result->getPayload()->toJson());
    }
}
