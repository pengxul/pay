<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\User;

use Pengxul\Pay\Contract\DirectionInterface;
use Pengxul\Pay\Plugin\Alipay\User\AgreementTransferPlugin;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;

class AgreementTransferPluginTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AgreementTransferPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(DirectionInterface::class, $result->getDirection());
        self::assertStringContainsString('alipay.user.agreement.transfer', $result->getPayload()->toJson());
        self::assertStringContainsString('CYCLE_PAY_AUTH_P', $result->getPayload()->toJson());
    }
}
