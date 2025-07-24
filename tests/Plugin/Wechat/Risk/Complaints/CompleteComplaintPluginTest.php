<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\Risk\Complaints;

use GuzzleHttp\Psr7\Uri;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Direction\OriginResponseDirection;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Wechat\Risk\Complaints\CompleteComplaintPlugin;
use Pengxul\Pay\Provider\Wechat;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class CompleteComplaintPluginTest extends TestCase
{
    /**
     * @var \Pengxul\Pay\Plugin\Wechat\Risk\Complaints\CompleteComplaintPlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new CompleteComplaintPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['complaint_id' => '123', 'foo' => 'bar']));

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket;});

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/merchant-service/complaints-v2/123/complete'), $radar->getUri());
        self::assertEquals(['complainted_mchid' => '1600314069'], $rocket->getPayload()->toArray());
        self::assertEquals('POST', $radar->getMethod());
        self::assertEquals(OriginResponseDirection::class, $result->getDirection());
    }

    public function testDirectMchId()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection(['complaint_id' => '456', 'complainted_mchid' => 'bar']));

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket;});

        $radar = $result->getRadar();

        self::assertEquals(new Uri(Wechat::URL[Pay::MODE_NORMAL].'v3/merchant-service/complaints-v2/456/complete'), $radar->getUri());
        self::assertEquals(['complainted_mchid' => 'bar'], $rocket->getPayload()->toArray());
        self::assertEquals(OriginResponseDirection::class, $result->getDirection());
    }

    public function testMissingId()
    {
        $rocket = new Rocket();
        $rocket->setParams([])->setPayload(new Collection());

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::MISSING_NECESSARY_PARAMS);

        $this->plugin->assembly($rocket, function ($rocket) {return $rocket;});
    }
}
