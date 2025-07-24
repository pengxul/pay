<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\User;

use Pengxul\Pay\Contract\DirectionInterface;
use Pengxul\Pay\Plugin\Alipay\User\AgreementExecutionPlanModifyPlugin;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;

class AgreementExecutionPlanModifyPluginTest extends TestCase
{
    protected AgreementExecutionPlanModifyPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AgreementExecutionPlanModifyPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals(DirectionInterface::class, $result->getDirection());
        self::assertStringContainsString('alipay.user.agreement.executionplan.modify', $result->getPayload()->toJson());
    }
}
