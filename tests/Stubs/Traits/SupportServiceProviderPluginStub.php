<?php

namespace Pengxul\Pay\Tests\Stubs\Traits;

use Pengxul\Artful\Rocket;
use Pengxul\Pay\Traits\SupportServiceProviderTrait;

class SupportServiceProviderPluginStub
{
    use SupportServiceProviderTrait;

    public function assembly(Rocket $rocket)
    {
        $this->loadAlipayServiceProvider($rocket);
    }
}
