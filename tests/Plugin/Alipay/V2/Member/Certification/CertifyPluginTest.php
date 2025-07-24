<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2\Member\Certification;

use Pengxul\Artful\Direction\ResponseDirection;
use Pengxul\Pay\Plugin\Alipay\V2\Member\Certification\CertifyPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class CertifyPluginTest extends TestCase
{
    protected CertifyPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new CertifyPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())
            ->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload()->toJson();

        self::assertNotEquals(ResponseDirection::class, $result->getDirection());
        self::assertStringContainsString('alipay.user.certify.open.certify', $payload);
    }
}
