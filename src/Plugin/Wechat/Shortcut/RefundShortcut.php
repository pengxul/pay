<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\Shortcut;

use Pengxul\Pay\Contract\ShortcutInterface;
use Pengxul\Pay\Plugin\Wechat\Pay\Common\RefundPlugin;

class RefundShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            RefundPlugin::class,
        ];
    }
}
