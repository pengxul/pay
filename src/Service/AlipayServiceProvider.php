<?php

declare(strict_types=1);

namespace Pengxul\Pay\Service;

use Pengxul\Pay\Contract\ServiceProviderInterface;
use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Provider\Alipay;

class AlipayServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        $service = new Alipay();

        Pay::set(Alipay::class, $service);
        Pay::set('alipay', $service);
    }
}
