<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\Shortcut;

use Pengxul\Pay\Contract\ShortcutInterface;
use Pengxul\Pay\Plugin\Wechat\Pay\Native\PrepayPlugin;

class ScanShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PrepayPlugin::class,
        ];
    }
}
