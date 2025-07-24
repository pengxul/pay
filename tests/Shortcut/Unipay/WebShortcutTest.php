<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Shortcut\Unipay;

use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Plugin\Unipay\AddRadarPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Unipay\Open\Pay\Web\PayPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\ResponseHtmlPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\StartPlugin;
use Pengxul\Pay\Shortcut\Unipay\WebShortcut;
use Pengxul\Pay\Tests\TestCase;

class WebShortcutTest extends TestCase
{
    protected WebShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new WebShortcut();
    }

    public function test()
    {
        self::assertEquals([
            StartPlugin::class,
            PayPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            ResponseHtmlPlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins([]));
    }
}
