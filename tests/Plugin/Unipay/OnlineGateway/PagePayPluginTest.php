<?php

namespace Pengxul\Pay\Tests\Plugin\Unipay\OnlineGateway;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Pengxul\Pay\Direction\ResponseDirection;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Unipay\OnlineGateway\PagePayPlugin;
use Pengxul\Pay\Provider\Unipay;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;

class PagePayPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Pay\Plugin\Unipay\OnlineGateway\PagePayPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PagePayPlugin();
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
        self::assertEquals(new Uri(Unipay::URL[Pay::MODE_NORMAL].'gateway/api/frontTransReq.do'), $radar->getUri());
        self::assertEquals(ResponseDirection::class, $result->getDirection());
        self::assertEquals('000201', $payload['bizType']);
        self::assertEquals('01', $payload['txnType']);
        self::assertEquals('01', $payload['txnSubType']);
        self::assertEquals('07', $payload['channelType']);
    }
}
