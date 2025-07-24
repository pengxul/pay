<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Plugin\Unipay\Shortcut;

use Pengxul\Pay\Plugin\Unipay\HtmlResponsePlugin;
use Pengxul\Pay\Plugin\Unipay\OnlineGateway\PagePayPlugin;
use Pengxul\Pay\Plugin\Unipay\Shortcut\WebShortcut;
use Pengxul\Pay\Tests\TestCase;

class WebShortcutTest extends TestCase
{
    protected $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new WebShortcut();
    }

    public function test()
    {
        self::assertEquals([
            PagePayPlugin::class,
            HtmlResponsePlugin::class,
        ], $this->plugin->getPlugins([]));
    }
}
