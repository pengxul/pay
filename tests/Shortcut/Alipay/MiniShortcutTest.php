<?php

namespace Pengxul\Pay\Tests\Shortcut\Alipay;

use Pengxul\Pay\Plugin\Alipay\V2\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\AddRadarPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\FormatPayloadBizContentPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Mini\PayPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\ResponsePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\StartPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\VerifySignaturePlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Shortcut\Alipay\MiniShortcut;
use Pengxul\Pay\Tests\TestCase;

class MiniShortcutTest extends TestCase
{
    protected MiniShortcut $shortcut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shortcut = new MiniShortcut();
    }

    public function testNormal()
    {
        $result = $this->shortcut->getPlugins([]);

        self::assertEquals([
            StartPlugin::class,
            PayPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }
}
