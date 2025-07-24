<?php

declare(strict_types=1);

namespace Pengxul\Pay\Event;

use Psr\Http\Message\ServerRequestInterface;
use Pengxul\Artful\Event\Event;
use Pengxul\Artful\Rocket;

class CallbackReceived extends Event
{
    public string $provider;

    public ?array $params = null;

    public null|array|ServerRequestInterface $contents;

    public function __construct(string $provider, null|array|ServerRequestInterface $contents, ?array $params = null, ?Rocket $rocket = null)
    {
        $this->provider = $provider;
        $this->contents = $contents;
        $this->params = $params;

        parent::__construct($rocket);
    }
}
