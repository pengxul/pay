<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Shortcut\Douyin;

use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Artful\Plugin\StartPlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\AddRadarPlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\Mini\PayPlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\ResponsePlugin;
use Pengxul\Pay\Shortcut\Douyin\MiniShortcut;
use Pengxul\Pay\Tests\TestCase;

class MiniShortcutTest extends TestCase
{
    protected MiniShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new MiniShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            StartPlugin::class,
            PayPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins([]));
    }
}
