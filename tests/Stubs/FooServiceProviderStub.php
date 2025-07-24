<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Stubs;

use Pengxul\Pay\Contract\ServiceProviderInterface;
use Pengxul\Pay\Pay;

class FooServiceProviderStub implements ServiceProviderInterface
{
    /**
     * @throws \Pengxul\Pay\Exception\ContainerException
     */
    public function register($data = null): void
    {
        Pay::set('foo', 'bar');
    }
}
