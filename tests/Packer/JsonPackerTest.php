<?php

namespace Pengxul\Pay\Tests\Packer;

use Pengxul\Pay\Packer\JsonPacker;

class JsonPackerTest extends \Pengxul\Pay\Tests\TestCase
{
    protected JsonPacker $packer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->packer = new JsonPacker();
    }

    public function testPack()
    {
        $array = ['name' => 'yansongda', 'age' => 29];
        $str = '{"name":"yansongda","age":29}';

        self::assertEquals($str, $this->packer->pack($array));
    }

    public function testUnpack()
    {
        $array = ['name' => 'yansongda', 'age' => 29];
        $str = '{"name":"yansongda","age":29}';

        self::assertEquals($array, $this->packer->unpack($str));
    }
}
