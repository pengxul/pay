<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Unipay\Shortcut;

use Pengxul\Pay\Contract\ShortcutInterface;
use Pengxul\Pay\Plugin\Unipay\HtmlResponsePlugin;
use Pengxul\Pay\Plugin\Unipay\OnlineGateway\WapPayPlugin;

class WapShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            WapPayPlugin::class,
            HtmlResponsePlugin::class,
        ];
    }
}
