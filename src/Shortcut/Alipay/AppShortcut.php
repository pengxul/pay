<?php

declare(strict_types=1);

namespace Pengxul\Pay\Shortcut\Alipay;

use Pengxul\Artful\Contract\ShortcutInterface;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\FormatPayloadBizContentPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\App\PayPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\ResponseInvokeStringPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\StartPlugin;

class AppShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            StartPlugin::class,
            PayPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            ResponseInvokeStringPlugin::class,
            ParserPlugin::class,
        ];
    }
}
