<?php

namespace Pengxul\Pay\Tests\Plugin\Unipay\Qra\Scan;

use Pengxul\Artful\Packer\XmlPacker;
use Pengxul\Pay\Plugin\Unipay\Qra\Scan\RefundPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Str;

class RefundPluginTest extends TestCase
{
    protected RefundPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new RefundPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'qra']);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload();

        self::assertEquals(XmlPacker::class, $result->getPacker());
        self::assertEquals('https://qra.95516.com/pay/gateway', $payload->get('_url'));
        self::assertEquals('unified.trade.refund', $payload->get('service'));
        self::assertEquals('UTF-8', $payload->get('charset'));
        self::assertEquals('MD5', $payload->get('sign_type'));
        self::assertEquals('QRA29045311KKR1', $payload->get('mch_id'));
        self::assertEquals(32, Str::length($payload->get('nonce_str')));
    }
}
