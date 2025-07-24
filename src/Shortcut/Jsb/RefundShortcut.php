<?php

declare(strict_types=1);

namespace Pengxul\Pay\Shortcut\Jsb;

use Pengxul\Artful\Contract\ShortcutInterface;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Plugin\Jsb\AddPayloadSignPlugin;
use Pengxul\Pay\Plugin\Jsb\AddRadarPlugin;
use Pengxul\Pay\Plugin\Jsb\Pay\Scan\RefundPlugin;
use Pengxul\Pay\Plugin\Jsb\ResponsePlugin;
use Pengxul\Pay\Plugin\Jsb\StartPlugin;
use Pengxul\Pay\Plugin\Jsb\VerifySignaturePlugin;

class RefundShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            StartPlugin::class,
            RefundPlugin::class,
            AddPayloadSignPlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }
}
