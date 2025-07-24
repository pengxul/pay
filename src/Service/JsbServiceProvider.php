<?php

declare(strict_types=1);

namespace Pengxul\Pay\Service;

use Pengxul\Artful\Contract\ServiceProviderInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Provider\Jsb;

class JsbServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws ContainerException
     */
    public function register(mixed $data = null): void
    {
        $service = new Jsb();

        Pay::set(Jsb::class, $service);
        Pay::set('jsb', $service);
    }
}
