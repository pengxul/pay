<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Plugin\Unipay\Shortcut;

use Pengxul\Pay\Plugin\Unipay\HtmlResponsePlugin;
use Pengxul\Pay\Plugin\Unipay\OnlineGateway\WapPayPlugin;
use Pengxul\Pay\Plugin\Unipay\Shortcut\WapShortcut;
use Pengxul\Pay\Tests\TestCase;

class WapShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new WapShortcut();
    }

    public function test()
    {
        self::assertEquals([
            WapPayPlugin::class,
            HtmlResponsePlugin::class,
        ], $this->plugin->getPlugins([]));
    }
}
