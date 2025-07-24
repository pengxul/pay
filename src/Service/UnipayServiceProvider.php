<?php

declare(strict_types=1);

namespace Pengxul\Pay\Service;

use Pengxul\Artful\Contract\ServiceProviderInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Provider\Unipay;

class UnipayServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws ContainerException
     */
    public function register(mixed $data = null): void
    {
        $service = new Unipay();

        Pay::set(Unipay::class, $service);
        Pay::set('unipay', $service);
    }
}
