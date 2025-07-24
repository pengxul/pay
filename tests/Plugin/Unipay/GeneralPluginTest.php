<?php

namespace Pengxul\Pay\Tests\Plugin\Unipay;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Provider\Unipay;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\Stubs\Plugin\UnipayGeneralPluginStub;
use Pengxul\Pay\Tests\Stubs\Plugin\UnipayGeneralPluginStub1;
use Pengxul\Pay\Tests\TestCase;

class GeneralPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Pay\Tests\Stubs\Plugin\UnipayGeneralPluginStub
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new UnipayGeneralPluginStub();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertInstanceOf(RequestInterface::class, $radar);
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(new Uri(Unipay::URL[Pay::MODE_NORMAL].'yansongda/pay'), $radar->getUri());
    }

    public function testAbsoluteUrl()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = (new UnipayGeneralPluginStub1())->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri('https://yansongda.cn/pay'), $radar->getUri());
    }
}
