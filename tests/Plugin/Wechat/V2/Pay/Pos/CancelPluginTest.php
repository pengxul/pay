<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V2\Pay\Pos;

use Pengxul\Artful\Packer\XmlPacker;
use Pengxul\Pay\Plugin\Wechat\V2\Pay\Pos\CancelPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class CancelPluginTest extends TestCase
{
    protected CancelPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new CancelPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "out_trade_no" => "111",
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $payload = $result->getPayload();

        self::assertEquals(XmlPacker::class, $result->getPacker());
        self::assertEquals('secapi/pay/reverse', $payload->get('_url'));
        self::assertEquals('application/xml', $payload->get('_content_type'));
        self::assertEquals('111', $payload->get('out_trade_no'));
        self::assertEquals('wx55955316af4ef13', $payload->get('appid'));
        self::assertEquals('1600314069', $payload->get('mch_id'));
        self::assertEquals('MD5', $payload->get('sign_type'));
        self::assertNotEmpty($payload->get('nonce_str'));
    }
}
