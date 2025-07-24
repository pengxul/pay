<?php

declare(strict_types=1);

namespace Pengxul\Pay\Direction;

use Psr\Http\Message\ResponseInterface;
use Pengxul\Pay\Contract\DirectionInterface;
use Pengxul\Pay\Contract\PackerInterface;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidResponseException;

class ArrayDirection implements DirectionInterface
{
    /**
     * @throws InvalidResponseException
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response): array
    {
        if (is_null($response)) {
            throw new InvalidResponseException(Exception::RESPONSE_NONE);
        }

        $body = (string) $response->getBody();

        if (!is_null($result = $packer->unpack($body))) {
            return $result;
        }

        throw new InvalidResponseException(Exception::UNPACK_RESPONSE_ERROR, 'Unpack Response Error', ['body' => $body, 'response' => $response]);
    }
}
