<?php

namespace Pengxul\Pay\Tests\Stubs\Traits;

use Pengxul\Pay\Rocket;
use Pengxul\Pay\Traits\SupportServiceProviderTrait;

class SupportServiceProviderPluginStub
{
    use SupportServiceProviderTrait;

    public function assembly(Rocket $rocket)
    {
        $this->loadAlipayServiceProvider($rocket);
    }
}
