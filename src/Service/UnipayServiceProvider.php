<?php

declare(strict_types=1);

namespace Pengxul\Pay\Service;

use Pengxul\Pay\Contract\ServiceProviderInterface;
use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Provider\Unipay;

class UnipayServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        $service = new Unipay();

        Pay::set(Unipay::class, $service);
        Pay::set('unipay', $service);
    }
}
