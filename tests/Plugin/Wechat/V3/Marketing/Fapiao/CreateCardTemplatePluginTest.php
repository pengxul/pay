<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Marketing\Fapiao;

use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\Fapiao\CreateCardTemplatePlugin;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class CreateCardTemplatePluginTest extends TestCase
{
    protected CreateCardTemplatePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new CreateCardTemplatePlugin();
    }

    public function testNormalParams()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "test" => "yansongda",
            'card_appid' => '1111',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/new-tax-control-fapiao/card-template',
            'test' => 'yansongda',
            'card_appid' => '1111',
        ], $result->getPayload()->all());
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            "test" => "yansongda",
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/new-tax-control-fapiao/card-template',
            'test' => 'yansongda',
            'card_appid' => 'wx55955316af4ef13',
        ], $result->getPayload()->all());
    }
}
