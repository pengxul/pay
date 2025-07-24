<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\Fund\Transfer;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\Fund\Transfer\QueryBatchIdPlugin;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class QueryBatchIdPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Pay\Plugin\Wechat\Fund\Transfer\QueryBatchIdPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryBatchIdPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['batch_id' => '123', 'need_query_detail' => false]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $url = $radar->getUri();

        self::assertEquals('/v3/transfer/batches/batch-id/123', $url->getPath());
        self::assertEquals('need_query_detail=0', $url->getQuery());
        self::assertEquals('GET', $radar->getMethod());
    }

    public function testNormalNoBatchId()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['need_query_detail' => false]));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalNoNeedQueryDetail()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['batch_id' => '123']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testPartner()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['batch_id' => '123', 'need_query_detail' => false]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $radar = $result->getRadar();
        $url = $radar->getUri();

        self::assertEquals('/v3/partner-transfer/batches/batch-id/123', $url->getPath());
        self::assertEquals('need_query_detail=0', $url->getQuery());
        self::assertEquals('GET', $radar->getMethod());
    }

    public function testPartnerNoBatchId()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['need_query_detail' => false]));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testPartnerNoNeedQueryDetail()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection(['batch_id' => '123']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }
}
