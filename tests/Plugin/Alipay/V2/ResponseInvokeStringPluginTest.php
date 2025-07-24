<?php

namespace Pengxul\Pay\Tests\Plugin\Alipay\V2;

use Pengxul\Pay\Plugin\Alipay\V2\ResponseInvokeStringPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class ResponseInvokeStringPluginTest extends TestCase
{
    private ResponseInvokeStringPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ResponseInvokeStringPlugin();
    }

    public function testNormal()
    {
        $payload = [
            'name' => "yansongda",
            'age' => 30,
        ];

        $rocket = new Rocket();
        $rocket->mergePayload($payload);

        $result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket;});

        self::assertEquals(http_build_query($payload), $result->getDestination()->getBody()->getContents());
    }
}
