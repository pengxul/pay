<?php

namespace Pengxul\Pay\Tests\Plugin\Douyin\V1\Pay;

use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\AddPayloadSignaturePlugin;
use Pengxul\Pay\Tests\TestCase;

class AddPayloadSignaturePluginTest extends TestCase
{
    protected AddPayloadSignaturePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new AddPayloadSignaturePlugin();
    }

    public function testSignNormal()
    {
        $rocket = new Rocket();

        $rocket->setPayload([
            '_foo' => 'bar',
            'out_order_no' => '202406100423024876',
            'total_amount' => 1,
            'subject' => '闫嵩达 - test - subject - 01',
            'body' => '闫嵩达 - test - body - 01',
            'valid_time' => 600,
            'notify_url' => 'https://yansongda.cn/douyin/notify',
        ]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('771c1952ffb5e0744fc0ad1337aafa6a', $result->getPayload()->get('sign'));
    }

    public function testSignContainsJsonString()
    {
        $rocket = new Rocket();

        $rocket->setPayload([
            '_foo' => 'bar',
            'out_order_no' => '202406101307142575',
            'total_amount' => 1,
            'subject' => '闫嵩达 - test - subject - 01',
            'body' => '闫嵩达 - test - body - 01',
            'valid_time' => 600,
            'notify_url' => 'https://yansongda.cn/douyin/notify',
            'expand_order_info' => '{"original_delivery_fee":15,"actual_delivery_fee":10}',
        ]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals('259702d0e950991b0bd494c9357f3ca4', $result->getPayload()->get('sign'));
    }

    public function testEmptySalt()
    {
        $rocket = new Rocket();
        $rocket->setParams(['_config' => 'empty_salt']);

        $rocket->setPayload([
            '_foo' => 'bar',
            'out_order_no' => '202406100423024876',
            'total_amount' => 1,
            'subject' => '闫嵩达 - test - subject - 01',
            'body' => '闫嵩达 - test - body - 01',
            'valid_time' => 600,
            'notify_url' => 'https://yansongda.cn/douyin/notify',
        ]);

        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(Exception::CONFIG_DOUYIN_INVALID);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }
}
