<?php

namespace Pengxul\Pay\Tests\Shortcut\Alipay;

use Pengxul\Pay\Plugin\Alipay\V2\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\AddRadarPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\FormatPayloadBizContentPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\H5\PayPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\ResponseHtmlPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\StartPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Shortcut\Alipay\H5Shortcut;
use Pengxul\Pay\Tests\TestCase;

class H5ShortcutTest extends TestCase
{
    protected H5Shortcut $shortcut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shortcut = new H5Shortcut();
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
            ResponseHtmlPlugin::class,
            ParserPlugin::class,
        ], $result);
    }
}
