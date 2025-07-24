<?php

declare(strict_types=1);

namespace Pengxul\Pay\Direction;

use Psr\Http\Message\ResponseInterface;
use Pengxul\Pay\Contract\DirectionInterface;
use Pengxul\Pay\Contract\PackerInterface;
use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Exception\ServiceNotFoundException;
use Pengxul\Pay\Pay;
use Pengxul\Supports\Collection;

class CollectionDirection implements DirectionInterface
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response): Collection
    {
        return new Collection(
            Pay::get(ArrayDirection::class)->parse($packer, $response)
        );
    }
}
