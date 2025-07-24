<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\Shortcut;

use Pengxul\Pay\Contract\ShortcutInterface;
use Pengxul\Pay\Plugin\Wechat\Pay\App\InvokePrepayPlugin;
use Pengxul\Pay\Plugin\Wechat\Pay\App\PrepayPlugin;

class AppShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PrepayPlugin::class,
            InvokePrepayPlugin::class,
        ];
    }
}
