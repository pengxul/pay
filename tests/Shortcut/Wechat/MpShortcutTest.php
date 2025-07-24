<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Shortcut\Wechat;

use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Artful\Plugin\StartPlugin;
use Pengxul\Pay\Plugin\Wechat\AddRadarPlugin;
use Pengxul\Pay\Plugin\Wechat\ResponsePlugin;
use Pengxul\Pay\Plugin\Wechat\V3\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Jsapi\InvokePlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Jsapi\PayPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\VerifySignaturePlugin;
use Pengxul\Pay\Shortcut\Wechat\MpShortcut;
use Pengxul\Pay\Tests\TestCase;

class MpShortcutTest extends TestCase
{
    protected MpShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new MpShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            StartPlugin::class,
            PayPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            InvokePlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins([]));
    }
}
