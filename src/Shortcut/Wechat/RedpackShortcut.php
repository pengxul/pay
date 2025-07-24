<?php

declare(strict_types=1);

namespace Pengxul\Pay\Shortcut\Wechat;

use Pengxul\Artful\Contract\ShortcutInterface;
use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Artful\Plugin\StartPlugin;
use Pengxul\Pay\Plugin\Wechat\AddRadarPlugin;
use Pengxul\Pay\Plugin\Wechat\ResponsePlugin;
use Pengxul\Pay\Plugin\Wechat\V2\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Wechat\V2\Pay\Redpack\SendPlugin;
use Pengxul\Pay\Plugin\Wechat\V2\VerifySignaturePlugin;

class RedpackShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            StartPlugin::class,
            SendPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }
}
