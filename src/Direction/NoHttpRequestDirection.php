<?php

declare(strict_types=1);

namespace Pengxul\Pay\Direction;

use Psr\Http\Message\ResponseInterface;
use Pengxul\Pay\Contract\DirectionInterface;
use Pengxul\Pay\Contract\PackerInterface;

class NoHttpRequestDirection implements DirectionInterface
{
    public function parse(PackerInterface $packer, ?ResponseInterface $response): ?ResponseInterface
    {
        return $response;
    }
}
