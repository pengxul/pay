<?php

namespace Pengxul\Pay\Tests\Traits;

use Pengxul\Pay\Pay;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Tests\Stubs\Traits\SupportServiceProviderPluginStub;
use Pengxul\Pay\Tests\TestCase;

class SupportServiceProviderTraitTest extends TestCase
{
    public function testNormal()
    {
        Pay::config([
           '_force' => true,
           'alipay' => [
               'default' => [
                   'mode' => Pay::MODE_SERVICE,
                   'service_provider_id' => 'yansongda'
               ]
           ]
        ]);

        $rocket = new Rocket();
        (new SupportServiceProviderPluginStub())->assembly($rocket);

        $result = json_encode($rocket->getParams());

        self::assertStringContainsString('extend_params', $result);
        self::assertStringContainsString('sys_service_provider_id', $result);
        self::assertStringContainsString('yansongda', $result);
    }
}
