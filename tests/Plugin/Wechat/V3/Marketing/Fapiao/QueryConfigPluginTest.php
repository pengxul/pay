<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Marketing\Fapiao;

use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\Fapiao\QueryConfigPlugin;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class QueryConfigPluginTest extends TestCase
{
    protected QueryConfigPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryConfigPlugin();
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "test" => "yansongda",
            'appid' => '1111',
            '_t' => 'a',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'GET',
            '_url' => 'v3/new-tax-control-fapiao/merchant/development-config',
        ], $result->getPayload()->all());
    }
}
