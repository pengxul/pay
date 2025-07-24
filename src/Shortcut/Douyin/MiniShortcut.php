<?php

declare(strict_types=1);

namespace Pengxul\Pay\Shortcut\Douyin;

use Pengxul\Artful\Contract\ShortcutInterface;
use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Artful\Plugin\StartPlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\AddRadarPlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\Mini\PayPlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\ResponsePlugin;

class MiniShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            StartPlugin::class,
            PayPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }
}
