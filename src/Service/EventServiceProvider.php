<?php

declare(strict_types=1);

namespace Pengxul\Pay\Service;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Pengxul\Pay\Contract\EventDispatcherInterface;
use Pengxul\Pay\Contract\ServiceProviderInterface;
use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Pay;

class EventServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     */
    public function register($data = null): void
    {
        if (class_exists(EventDispatcher::class)) {
            Pay::set(EventDispatcherInterface::class, new EventDispatcher());
        }
    }
}
