<?php

namespace Pengxul\Pay\Tests\Plugin\Douyin\V1\Pay\Mini;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\Mini\PayPlugin;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class PayPluginTest extends TestCase
{
    protected PayPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PayPlugin();
    }

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: 抖音小程序下单，参数为空');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "name" => "yansongda",
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            "name" => "yansongda",
            '_method' => 'POST',
            '_url' => 'api/apps/ecpay/v1/create_order',
            'app_id' => 'tt226e54d3bd581bf801',
            'notify_url' => 'https://yansongda.cn/douyin/notify',
        ], $result->getPayload()->all());
    }

    public function testServiceParams()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection([
            'name' => 'yansongda',
            'thirdparty_id' => 'service_provider111',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            'name' => 'yansongda',
            '_method' => 'POST',
            '_url' => 'api/apps/ecpay/v1/create_order',
            'app_id' => 'tt226e54d3bd581bf801',
            'notify_url' => 'https://yansongda.cn/douyin/notify',
            'thirdparty_id' => 'service_provider111'
        ], $result->getPayload()->all());
    }

    public function testService()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'service_provider'])->setPayload(new Collection([
            'name' => 'yansongda',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            'name' => 'yansongda',
            '_method' => 'POST',
            '_url' => 'api/apps/ecpay/v1/create_order',
            'app_id' => 'tt226e54d3bd581bf801',
            'notify_url' => 'https://yansongda.cn/douyin/notify',
            'thirdparty_id' => 'service_provider'
        ], $result->getPayload()->all());
    }
}
