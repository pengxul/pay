<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Member\FaceVerification;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Member\FaceVerification\AppQueryPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class AppQueryPluginTest extends TestCase
{
    protected AppQueryPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AppQueryPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('datadigital.fincloud.generalsaas.face.verification.query', $result->getPayload()->toJson());
    }
}
