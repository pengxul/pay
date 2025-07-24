<?php

namespace Pengxul\Pay\Tests\Shortcut\Alipay;

use Pengxul\Pay\Plugin\Alipay\V2\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\FormatPayloadBizContentPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\App\PayPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\ResponseInvokeStringPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\StartPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Shortcut\Alipay\AppShortcut;
use Pengxul\Pay\Tests\TestCase;

class AppShortcutTest extends TestCase
{
    protected AppShortcut $shortcut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shortcut = new AppShortcut();
    }

    public function testNormal()
    {
        $result = $this->shortcut->getPlugins([]);

        self::assertEquals([
            StartPlugin::class,
            PayPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            ResponseInvokeStringPlugin::class,
            ParserPlugin::class,
        ], $result);
    }
}
