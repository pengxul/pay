<?php

namespace Pengxul\Pay\Tests\Direction;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidResponseException;
use Pengxul\Pay\Packer\JsonPacker;
use Pengxul\Pay\Direction\OriginResponseDirection;
use Pengxul\Pay\Tests\TestCase;

class OriginResponseDirectionTest extends TestCase
{
    protected OriginResponseDirection $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new OriginResponseDirection();
    }

    public function testResponseNull()
    {
        self::expectException(InvalidResponseException::class);
        self::expectExceptionCode(Exception::INVALID_RESPONSE_CODE);

        $this->parser->parse(new JsonPacker(), null);
    }
}
