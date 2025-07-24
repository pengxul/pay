<?php

declare(strict_types=1);

namespace Pengxul\Pay\Event;

use Pengxul\Pay\Contract\PluginInterface;
use Pengxul\Pay\Rocket;

class PayStarted extends Event
{
    /**
     * @var PluginInterface[]
     */
    public array $plugins;

    public array $params;

    public function __construct(array $plugins, array $params, ?Rocket $rocket = null)
    {
        $this->plugins = $plugins;
        $this->params = $params;

        parent::__construct($rocket);
    }
}
