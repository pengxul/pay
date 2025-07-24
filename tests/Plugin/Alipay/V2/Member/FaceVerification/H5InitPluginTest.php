<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Member\FaceVerification;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Member\FaceVerification\H5InitPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class H5InitPluginTest extends TestCase
{
    protected H5InitPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new H5InitPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('datadigital.fincloud.generalsaas.face.certify.initialize', $result->getPayload()->toJson());
        self::assertStringContainsString('FUTURE_TECH_BIZ_FACE_SDK', $result->getPayload()->toJson());
    }
}
