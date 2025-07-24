<?php

namespace Pengxul\Pay\Tests\Shortcut\Jsb;

use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Plugin\Jsb\AddPayloadSignPlugin;
use Pengxul\Pay\Plugin\Jsb\AddRadarPlugin;
use Pengxul\Pay\Plugin\Jsb\Pay\Scan\RefundPlugin;
use Pengxul\Pay\Plugin\Jsb\ResponsePlugin;
use Pengxul\Pay\Plugin\Jsb\StartPlugin;
use Pengxul\Pay\Plugin\Jsb\VerifySignaturePlugin;
use Pengxul\Pay\Shortcut\Jsb\RefundShortcut;
use Pengxul\Pay\Tests\TestCase;

class RefundShortcutTest extends TestCase
{
	protected RefundShortcut $plugin;

	protected function setUp(): void
	{
		parent::setUp();

		$this->plugin = new RefundShortcut();
	}

	public function testDefault()
	{
		self::assertEquals([
			StartPlugin::class,
			RefundPlugin::class,
			AddPayloadSignPlugin::class,
			AddRadarPlugin::class,
			VerifySignaturePlugin::class,
			ResponsePlugin::class,
			ParserPlugin::class,
		], $this->plugin->getPlugins([]));
	}
}
