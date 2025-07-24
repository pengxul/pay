<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat;

use GuzzleHttp\Psr7\Response;
use Pengxul\Artful\Direction\OriginResponseDirection;
use Pengxul\Artful\Exception\InvalidResponseException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Wechat\ResponsePlugin;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class ResponsePluginTest extends TestCase
{
    protected ResponsePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ResponsePlugin();
    }

    public function testOriginalResponseDestination()
    {
        $destination = new Response();

        $rocket = new Rocket();
        $rocket->setDestinationOrigin($destination);
        $rocket->setDestination(new Collection());

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertInstanceOf(Collection::class, $result->getDestination());
    }

    public function testOriginalResponseCodeErrorDestination()
    {
        $destination = new Response(500);

        $rocket = new Rocket();
        $rocket->setDestinationOrigin($destination);

        self::expectException(InvalidResponseException::class);
        self::expectExceptionCode(Exception::RESPONSE_CODE_WRONG);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }
}
