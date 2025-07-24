<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Member\Ocr;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Member\Ocr\DetectPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class DetectPluginTest extends TestCase
{
    protected DetectPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new DetectPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('datadigital.fincloud.generalsaas.ocr.common.detect', $result->getPayload()->toJson());
    }
}
