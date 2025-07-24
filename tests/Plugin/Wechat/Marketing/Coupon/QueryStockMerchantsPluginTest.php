<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\Marketing\Coupon;

use GuzzleHttp\Psr7\Uri;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Wechat\Marketing\Coupon\QueryStockMerchantsPlugin;
use Pengxul\Pay\Provider\Wechat;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class QueryStockMerchantsPluginTest extends TestCase
{
    protected QueryStockMerchantsPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryStockMerchantsPlugin();
    }

    public function testNormal()
    {
        $rocket = (new Rocket())->setParams([])->setPayload(new Collection([
            'stock_id' => '123456',
            'limit' => 1,
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });

        $radar = $result->getRadar();

        self::assertEquals('GET', $radar->getMethod());
        self::assertNull($result->getPayload());
        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/marketing/favor/stocks/123456/merchants?limit=1&stock_creator_mchid=1600314069'), $radar->getUri());
    }

    public function testException()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $rocket = (new Rocket())->setParams([])->setPayload(new Collection());

        $this->plugin->assembly($rocket, function ($rocket) {return $rocket; });
    }
}
