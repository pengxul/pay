<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Member\FaceCheck;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Member\FaceCheck\AppInitPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class AppInitPluginTest extends TestCase
{
    protected AppInitPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AppInitPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('datadigital.fincloud.generalsaas.face.check.initialize', $result->getPayload()->toJson());
        self::assertStringContainsString('DATA_DIGITAL_BIZ_CODE_FACE_CHECK_LIVE', $result->getPayload()->toJson());
    }
}
