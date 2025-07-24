<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\Shortcut;

use Pengxul\Pay\Contract\ShortcutInterface;
use Pengxul\Pay\Plugin\Alipay\Fund\TransUniTransferPlugin;

class TransferShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            TransUniTransferPlugin::class,
        ];
    }
}
