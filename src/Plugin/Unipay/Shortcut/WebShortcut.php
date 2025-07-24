<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Unipay\Shortcut;

use Pengxul\Pay\Contract\ShortcutInterface;
use Pengxul\Pay\Plugin\Unipay\HtmlResponsePlugin;
use Pengxul\Pay\Plugin\Unipay\OnlineGateway\PagePayPlugin;

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
