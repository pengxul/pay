<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Shortcut\Wechat;

use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Artful\Plugin\StartPlugin;
use Pengxul\Pay\Shortcut\Wechat\RedpackShortcut;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Pay\Plugin\Wechat\AddRadarPlugin;
use Pengxul\Pay\Plugin\Wechat\ResponsePlugin;
use Pengxul\Pay\Plugin\Wechat\V2\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Wechat\V2\Pay\Redpack\SendPlugin;
use Pengxul\Pay\Plugin\Wechat\V2\VerifySignaturePlugin;

class RedpackShortcutTest extends TestCase
{
    protected RedpackShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new RedpackShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            StartPlugin::class,
            SendPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins([]));
    }
}
