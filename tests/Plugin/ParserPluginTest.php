<?php

namespace Pengxul\Pay\Tests\Plugin;

use Pengxul\Pay\Contract\DirectionInterface;
use Pengxul\Pay\Exception\InvalidConfigException;
use Pengxul\Pay\Direction\NoHttpRequestDirection;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\ParserPlugin;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Tests\Stubs\FooPackerStub;
use Pengxul\Pay\Tests\Stubs\FooParserStub;
use Pengxul\Pay\Tests\TestCase;

class ParserPluginTest extends TestCase
{
    protected ParserPlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ParserPlugin();
    }

    public function testWrongParser()
    {
        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(InvalidConfigException::INVALID_PARSER);

        $rocket = new Rocket();
        $rocket->setDirection(FooParserStub::class);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testWrongPacker()
    {
        self::expectException(InvalidConfigException::class);
        self::expectExceptionCode(InvalidConfigException::INVALID_PACKER);

        $rocket = new Rocket();
        $rocket->setPacker(FooPackerStub::class);

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testDefaultParser()
    {
        Pay::set(DirectionInterface::class, NoHttpRequestDirection::class);

        $rocket = new Rocket();

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertSame($rocket, $result);
    }

    public function testObjectParser()
    {
        Pay::set(DirectionInterface::class, new NoHttpRequestDirection());

        $rocket = new Rocket();

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertSame($rocket, $result);
    }
}
