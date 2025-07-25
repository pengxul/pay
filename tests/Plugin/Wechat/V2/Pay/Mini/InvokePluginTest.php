<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V2\Pay\Mini;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidResponseException;
use Pengxul\Pay\Plugin\Wechat\V2\Pay\Mini\InvokePlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class InvokePluginTest extends TestCase
{
    protected InvokePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new InvokePlugin();
    }

    public function testMissingPrepayId()
    {
        $rocket = new Rocket();

        self::expectException(InvalidResponseException::class);
        self::expectExceptionCode(Exception::RESPONSE_MISSING_NECESSARY_PARAMS);
        self::expectExceptionMessage('预下单失败：响应缺少 `prepay_id` 参数，请自行检查参数是否符合微信要求');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormalParams()
    {
        $rocket = (new Rocket())
            ->setDestination(new Collection(['prepay_id' => 'yansongda']))
            ->setPayload(['_invoke_appid' => '111']);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();

        self::assertArrayHasKey('paySign', $contents->all());
        self::assertArrayHasKey('timeStamp', $contents->all());
        self::assertArrayHasKey('nonceStr', $contents->all());
        self::assertEquals('111', $contents->get('appId'));
        self::assertEquals('prepay_id=yansongda', $contents->get('package'));
        self::assertEquals('MD5', $contents->get('signType'));
    }

    public function testNormal()
    {
        $rocket = (new Rocket())->setDestination(new Collection(['prepay_id' => 'yansongda']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();

        self::assertArrayHasKey('paySign', $contents->all());
        self::assertArrayHasKey('timeStamp', $contents->all());
        self::assertArrayHasKey('nonceStr', $contents->all());
        self::assertEquals('wx55955316af4ef14', $contents->get('appId'));
        self::assertEquals('prepay_id=yansongda', $contents->get('package'));
        self::assertEquals('MD5', $contents->get('signType'));
    }

    public function testServiceParams()
    {
        $rocket = (new Rocket())
            ->setParams(['_config' => 'service_provider4'])
            ->setDestination(new Collection(['prepay_id' => 'yansongda']))
            ->setPayload(['_invoke_appid' => '111']);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();

        self::assertArrayHasKey('paySign', $contents->all());
        self::assertArrayHasKey('timeStamp', $contents->all());
        self::assertArrayHasKey('nonceStr', $contents->all());
        self::assertEquals('111', $contents->get('appId'));
        self::assertEquals('prepay_id=yansongda', $contents->get('package'));
        self::assertEquals('MD5', $contents->get('signType'));
    }

    public function testService()
    {
        $rocket = (new Rocket())
            ->setParams(['_config' => 'service_provider4'])
            ->setDestination(new Collection(['prepay_id' => 'yansongda']));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $contents = $result->getDestination();

        self::assertArrayHasKey('paySign', $contents->all());
        self::assertArrayHasKey('timeStamp', $contents->all());
        self::assertArrayHasKey('nonceStr', $contents->all());
        self::assertEquals('wx55955316af4ef17', $contents->get('appId'));
        self::assertEquals('prepay_id=yansongda', $contents->get('package'));
        self::assertEquals('MD5', $contents->get('signType'));
    }
}