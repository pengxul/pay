<?php

declare(strict_types=1);

namespace Pengxul\Pay\Service;

use Pengxul\Artful\Contract\ServiceProviderInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Provider\Wechat;

class WechatServiceProvider implements ServiceProviderInterface
{
    /**
     * @throws ContainerException
     */
    public function register(mixed $data = null): void
    {
        $service = new Wechat();

        Pay::set(Wechat::class, $service);
        Pay::set('wechat', $service);
    }
}
