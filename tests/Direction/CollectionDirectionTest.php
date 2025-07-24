<?php

namespace Pengxul\Pay\Tests\Direction;

use GuzzleHttp\Psr7\Response;
use Pengxul\Pay\Packer\JsonPacker;
use Pengxul\Pay\Direction\CollectionDirection;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Tests\TestCase;

class CollectionDirectionTest extends TestCase
{
    protected CollectionDirection $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new CollectionDirection();
    }

    public function testNormal()
    {
        Pay::config();

        $response = new Response(200, [], '{"name": "yansongda"}');

        $result = $this->parser->parse(new JsonPacker(), $response);

        self::assertEquals(['name' => 'yansongda'], $result->all());
    }
}
