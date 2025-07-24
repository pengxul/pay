<?php

declare(strict_types=1);

namespace Pengxul\Pay\Service;

use Pengxul\Artful\Contract\ServiceProviderInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Provider\Alipay;

class AlipayServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws ContainerException
     */
    public function register(mixed $data = null): void
    {
        $service = new Alipay();

        Pay::set(Alipay::class, $service);
        Pay::set('alipay', $service);
    }
}
