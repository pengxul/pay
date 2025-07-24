<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V2\Pay\Redpack;

use Pengxul\Artful\Packer\XmlPacker;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Wechat\V2\Pay\Redpack\SendPlugin;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class SendPluginTest extends TestCase
{
    protected SendPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new SendPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "mch_billno" => "111",
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $payload = $result->getPayload();

        self::assertEquals(XmlPacker::class, $result->getPacker());
        self::assertEquals('mmpaymkttransfers/sendredpack', $payload->get('_url'));
        self::assertEquals('application/xml', $payload->get('_content_type'));
        self::assertEquals('111', $payload->get('mch_billno'));
        self::assertEquals('wx55955316af4ef13', $payload->get('wxappid'));
        self::assertEquals('1600314069', $payload->get('mch_id'));
        self::assertNotEmpty($payload->get('nonce_str'));
    }
}
