<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V2;

use GuzzleHttp\Psr7\Response;
use Pengxul\Artful\Direction\NoHttpRequestDirection;
use Pengxul\Pay\Plugin\Wechat\V2\VerifySignaturePlugin;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class VerifySignaturePluginTest extends TestCase
{
    protected VerifySignaturePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new VerifySignaturePlugin();
    }

    public function testShouldNotDoRequest()
    {
        $rocket = new Rocket();
        $rocket->setDirection(NoHttpRequestDirection::class)->setDestinationOrigin(new Response());
        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
        self::assertSame($rocket, $result);
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setDestination(new Collection(['name' => 'yansongda', 'age' => 29, 'foo' => '', 'sign' => '3213848AED2C380749FD1D559555881D']));

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertTrue(true);
    }
}
