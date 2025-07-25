<?php

namespace Pengxul\Pay\Tests\Plugin\Unipay\Qra;

use Pengxul\Pay\Plugin\Unipay\Qra\StartPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class StartPluginTest extends TestCase
{
    protected StartPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new StartPlugin();
    }

    public function testNormal()
    {
        $params = [
            'txnTime' => '20220903065448',
            'txnAmt' => 1,
            'orderId' => 'yansongda20220903065448',
        ];

        $rocket = (new Rocket())->setParams($params);

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals($params, $result->getPayload()->all());
    }
}
