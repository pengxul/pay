<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\Fund;

use Pengxul\Pay\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\Fund\AccountQueryPlugin;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;

class AccountQueryPluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AccountQueryPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.fund.account.query', $result->getPayload()->toJson());
        self::assertStringContainsString('TRANS_ACCOUNT_NO_PWD', $result->getPayload()->toJson());
    }
}
