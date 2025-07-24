<?php

namespace Pengxul\Pay\Tests\Shortcut\Jsb;

use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Plugin\Jsb\AddPayloadSignPlugin;
use Pengxul\Pay\Plugin\Jsb\AddRadarPlugin;
use Pengxul\Pay\Plugin\Jsb\Pay\Scan\PayPlugin;
use Pengxul\Pay\Plugin\Jsb\ResponsePlugin;
use Pengxul\Pay\Plugin\Jsb\StartPlugin;
use Pengxul\Pay\Plugin\Jsb\VerifySignaturePlugin;
use Pengxul\Pay\Shortcut\Jsb\ScanShortcut;
use Pengxul\Pay\Tests\TestCase;

class ScanShortcutTest extends TestCase
{
	protected ScanShortcut $plugin;

	protected function setUp(): void
	{
		parent::setUp();

		$this->plugin = new ScanShortcut();
	}

	public function testDefault()
	{
		self::assertEquals([
			StartPlugin::class,
			PayPlugin::class,
			AddPayloadSignPlugin::class,
			AddRadarPlugin::class,
			VerifySignaturePlugin::class,
			ResponsePlugin::class,
			ParserPlugin::class,
		], $this->plugin->getPlugins([]));
	}
}
