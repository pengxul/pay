<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\Fund\Profitsharing;

use GuzzleHttp\Psr7\Uri;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Wechat\Fund\Profitsharing\QueryPlugin;
use Pengxul\Pay\Provider\Wechat;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class QueryPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Pay\Plugin\Wechat\Fund\Profitsharing\QueryPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['out_order_no' => '123','transaction_id' => '456']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/profitsharing/orders/123?transaction_id=456'), $radar->getUri());
        self::assertEquals('GET', $radar->getMethod());
    }

    public function testNormalNoOutTradeNo()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['transaction_id' => '456']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalNoTransactionId()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['out_order_no' => '123']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testPartner()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['out_order_no' => '123','transaction_id' => '456']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_SERVICE].'v3/profitsharing/orders/123?transaction_id=456&sub_mchid=1600314070'), $radar->getUri());
        self::assertEquals('GET', $radar->getMethod());
    }

    public function testPartnerDirectPayload()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['out_order_no' => '123','transaction_id' => '456','sub_mchid' => '123']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_SERVICE].'v3/profitsharing/orders/123?transaction_id=456&sub_mchid=123'), $radar->getUri());
        self::assertEquals('GET', $radar->getMethod());
    }
}
