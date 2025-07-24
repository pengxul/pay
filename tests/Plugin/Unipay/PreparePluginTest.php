<?php

namespace Pengxul\Pay\Tests\Plugin\Unipay;

use Pengxul\Pay\Contract\ConfigInterface;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Unipay\PreparePlugin;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\TestCase;
use function Pengxul\Pay\get_unipay_config;

class PreparePluginTest extends TestCase
{
    /**
     * @var \Pengxul\Pay\Plugin\Unipay\PreparePlugin
     */
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PreparePlugin();
    }

    public function testNormal()
    {
        $params = [
            'txnTime' => '20220903065448',
            'txnAmt' => 1,
            'orderId' => 'yansongda20220903065448',
        ];
        $payload = array_merge($params, [
            'version' => '5.1.0',
            'encoding' => 'utf-8',
            'backUrl' => 'https://yansongda.cn/unipay/notify',
            'accessType' => '0',
            'signature' => '',
            'signMethod' => '01',
            'merId' => '777290058167151',
            'frontUrl' => 'https://yansongda.cn/unipay/return',
            'certId' => '69903319369',
            'currencyCode' => '156',
        ]);

        $rocket = (new Rocket())->setParams($params);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        $config = get_unipay_config([]);

        self::assertEquals($payload, $result->getPayload()->all());
        self::assertArrayHasKey('cert', $config['certs']);
        self::assertArrayHasKey('pkey', $config['certs']);
        self::assertEquals('69903319369', $config['certs']['cert_id']);

        Pay::get(ConfigInterface::class)->set('unipay.default.mch_cert_path', null);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertTrue(true);
    }
}
