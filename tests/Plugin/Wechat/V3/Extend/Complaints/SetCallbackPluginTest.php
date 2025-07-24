<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Extend\Complaints;

use Pengxul\Pay\Plugin\Wechat\V3\Extend\Complaints\SetCallbackPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class SetCallbackPluginTest extends TestCase
{
    protected SetCallbackPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new SetCallbackPlugin();
    }

    public function testNormal()
    {
        $payload = [
            "url" => "yansongda",
        ];

        $rocket = new Rocket();
        $rocket->setPayload(new Collection($payload));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/merchant-service/complaint-notifications',
            '_service_url' => 'v3/merchant-service/complaint-notifications',
            'url' => 'yansongda',
        ], $result->getPayload()->all());
    }
}
