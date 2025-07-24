<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\Pay\Pos;

use GuzzleHttp\Psr7\Uri;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Wechat\Pay\Pos\PayPlugin;
use Pengxul\Pay\Provider\Wechat;
use Pengxul\Pay\Rocket;
use Pengxul\Supports\Collection;

class PayPluginTest extends \Pengxul\Pay\Tests\TestCase
{
    protected PayPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PayPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['out_trade_no' => '123']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();
        $params = $result->getParams();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'pay/micropay'), $radar->getUri());
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals('1600314069', $payload->get('mch_id'));
        self::assertEquals('wx55955316af4ef13', $payload->get('appid'));
        self::assertEquals('v2', $params['_version']);
        self::assertEquals('application/xml', $radar->getHeaderLine('Content-Type'));
        self::assertEquals('yansongda/pay-v3', $radar->getHeaderLine('User-Agent'));
    }

    public function testMultiType()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_type' => 'app'])->setPayload(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'pay/micropay'), $radar->getUri());
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals('1600314069', $payload->get('mch_id'));
        self::assertEquals('yansongda', $payload->get('appid'));
    }
}