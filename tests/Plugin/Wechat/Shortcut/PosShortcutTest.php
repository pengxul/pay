<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Plugin\Wechat\Shortcut;

use Pengxul\Pay\Plugin\Wechat\Pay\Pos\PayPlugin;
use Pengxul\Pay\Plugin\Wechat\Shortcut\PosShortcut;
use Pengxul\Pay\Tests\TestCase;

class PosShortcutTest extends TestCase
{
    protected PosShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PosShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            PayPlugin::class,
        ], $this->plugin->getPlugins([]));
    }
}
