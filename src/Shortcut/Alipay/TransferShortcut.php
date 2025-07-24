<?php

declare(strict_types=1);

namespace Pengxul\Pay\Shortcut\Alipay;

use Pengxul\Artful\Contract\ShortcutInterface;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\AddRadarPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\FormatPayloadBizContentPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Fund\Transfer\Fund\TransferPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\ResponsePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\StartPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\VerifySignaturePlugin;

class TransferShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            StartPlugin::class,
            TransferPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }
}
