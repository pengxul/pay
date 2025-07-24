<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Pengxul\Pay\Packer\XmlPacker;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Provider\Wechat;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\Stubs\Plugin\WechatGeneralV2PluginStub;
use Pengxul\Pay\Tests\TestCase;

class GeneralV2PluginTest extends TestCase
{
    protected WechatGeneralV2PluginStub $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new WechatGeneralV2PluginStub();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $params = $result->getParams();
        $payload = $result->getPayload();

        self::assertEquals(XmlPacker::class, $result->getPacker());
        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'yansongda/pay'), $radar->getUri());
        self::assertEquals('1600314069', $payload->get('mch_id'));
        self::assertEquals('wx55955316af4ef13', $payload->get('appid'));
        self::assertEquals('v2', $params['_version']);
        self::assertEquals('application/xml', $radar->getHeaderLine('Content-Type'));
        self::assertEquals('yansongda/pay-v3', $radar->getHeaderLine('User-Agent'));
    }
}
