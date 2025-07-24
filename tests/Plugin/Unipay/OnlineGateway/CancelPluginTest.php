<?php

namespace Pengxul\Pay\Tests\Plugin\Unipay\OnlineGateway;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Unipay\OnlineGateway\CancelPlugin;
use Pengxul\Pay\Provider\Unipay;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;

class CancelPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Pay\Plugin\Unipay\OnlineGateway\CancelPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new CancelPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $payload = $result->getPayload();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Unipay::URL[Pay::MODE_NORMAL].'gateway/api/backTransReq.do'), $radar->getUri());
        self::assertEquals('000000', $payload['bizType']);
        self::assertEquals('31', $payload['txnType']);
        self::assertEquals('00', $payload['txnSubType']);
        self::assertEquals('07', $payload['channelType']);
    }
}
