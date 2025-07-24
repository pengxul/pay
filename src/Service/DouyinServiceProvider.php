<?php

declare(strict_types=1);

namespace Pengxul\Pay\Service;

use Pengxul\Artful\Contract\ServiceProviderInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Provider\Douyin;

class DouyinServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws ContainerException
     */
    public function register(mixed $data = null): void
    {
        $service = new Douyin();

        Pay::set(Douyin::class, $service);
        Pay::set('douyin', $service);
    }
}
