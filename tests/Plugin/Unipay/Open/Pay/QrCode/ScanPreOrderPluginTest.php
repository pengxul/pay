<?php

namespace Pengxul\Pay\Tests\Plugin\Unipay\Open\Pay\QrCode;

use Pengxul\Artful\Packer\QueryPacker;
use Pengxul\Pay\Plugin\Unipay\Open\Pay\QrCode\ScanPreOrderPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class ScanPreOrderPluginTest extends TestCase
{
    protected ScanPreOrderPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ScanPreOrderPlugin();
    }

    public function testNormalParams()
    {
        $rocket = new Rocket();
        $rocket->setPayload([
            'accessType' => '1',
            'bizType' => '2',
            'txnType' => '3',
            'txnSubType' => '4',
            'channelType' => '5',
        ]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload();

        self::assertEquals(QueryPacker::class, $result->getPacker());
        self::assertEquals([
            '_url' => 'gateway/api/order.do',
            'encoding' => 'utf-8',
            'signature' => '',
            'bizType' => '2',
            'accessType' => '1',
            'merId' => '777290058167151',
            'currencyCode' => '156',
            'channelType' => '5',
            'signMethod' => '01',
            'txnType' => '3',
            'txnSubType' => '4',
            'backUrl' => 'https://pay.yansongda.cn',
            'version' => '5.1.0',
            'frontUrl' => 'https://pay.yansongda.cn',
        ], $payload->all());
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setParams([]);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        $payload = $result->getPayload();

        self::assertEquals(QueryPacker::class, $result->getPacker());
        self::assertEquals([
            '_url' => 'gateway/api/order.do',
            'encoding' => 'utf-8',
            'signature' => '',
            'bizType' => '000000',
            'accessType' => '0',
            'merId' => '777290058167151',
            'currencyCode' => '156',
            'channelType' => '08',
            'signMethod' => '01',
            'txnType' => '01',
            'txnSubType' => '01',
            'backUrl' => 'https://pay.yansongda.cn',
            'version' => '5.1.0',
            'frontUrl' => 'https://pay.yansongda.cn',
        ], $payload->all());
    }
}
