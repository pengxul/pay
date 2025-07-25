<?php

namespace Pengxul\Pay\Tests\Plugin\Wechat\V3\Marketing\Fapiao\Blockchain;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\Fapiao\Blockchain\ReversePlugin;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;

class ReversePluginTest extends TestCase
{
    protected ReversePlugin $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new ReversePlugin();
    }

    public function testEmptyPayload()
    {
        $rocket = new Rocket();

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
        self::expectExceptionMessage('参数异常: 冲红电子发票，参数缺少 `fapiao_apply_id`');

        $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
    }

    public function testNormal()
    {
        $rocket = new Rocket();
        $rocket->setPayload(new Collection( [
            'fapiao_apply_id' => '111',
            'reason' => '222',
            '_t' => 'a',
        ]));

        $result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });

        self::assertEquals([
            '_method' => 'POST',
            '_url' => 'v3/new-tax-control-fapiao/fapiao-applications/111/reverse',
            'reason' => '222',
        ], $result->getPayload()->all());
    }
}
