<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Extend\Complaints;

use Pengxul\Pay\Plugin\Wechat\V3\Extend\Complaints\QueryCallbackPlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;

class QueryCallbackPluginTest extends TestCase
{
    protected QueryCallbackPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryCallbackPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'v3/merchant-service/complaint-notifications',
            '_service_url' => 'v3/merchant-service/complaint-notifications',
        ], $result->getPayload()->all());
    }
}
