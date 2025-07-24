<?php

declare(strict_types=1);

namespace Pengxul\Pay\Direction;

use Psr\Http\Message\ResponseInterface;
use Pengxul\Pay\Contract\DirectionInterface;
use Pengxul\Pay\Contract\PackerInterface;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidResponseException;

class OriginResponseDirection implements DirectionInterface
{
    /**
     * @throws InvalidResponseException
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response): ?ResponseInterface
    {
        if (!is_null($response)) {
            return $response;
        }

        throw new InvalidResponseException(Exception::INVALID_RESPONSE_CODE);
    }
}
