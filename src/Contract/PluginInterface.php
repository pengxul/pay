<?php

declare(strict_types=1);

namespace Pengxul\Pay\Contract;

use Closure;
use Pengxul\Pay\Rocket;

interface PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket;
}
