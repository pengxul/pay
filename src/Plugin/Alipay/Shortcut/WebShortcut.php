<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\Shortcut;

use Pengxul\Pay\Contract\ShortcutInterface;
use Pengxul\Pay\Plugin\Alipay\HtmlResponsePlugin;
use Pengxul\Pay\Plugin\Alipay\Trade\PagePayPlugin;

class WebShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PagePayPlugin::class,
            HtmlResponsePlugin::class,
        ];
    }
}
