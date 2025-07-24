<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Stubs;

use Pengxul\Artful\Contract\ServiceProviderInterface;
use Pengxul\Pay\Pay;

class FooServiceProviderStub implements ServiceProviderInterface
{
    /**
     * @throws \Pengxul\Pay\Exception\ContainerException
     */
    public function register(mixed $data = null): void
    {
        Pay::set('foo', 'bar');
    }
}
