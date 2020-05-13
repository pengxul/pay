<?php

namespace Pengxul\Pay\Tests\Gateways;

use Symfony\Component\HttpFoundation\Response;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Tests\TestCase;

class AlipayTest extends TestCase
{
    public function testSuccess()
    {
        $success = Pay::alipay([])->success();

        $this->assertInstanceOf(Response::class, $success);
        $this->assertEquals('success', $success->getContent());
    }
}
