<?php

namespace Pengxul\Pay\Tests\Shortcut\Jsb;

use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Plugin\Jsb\AddPayloadSignPlugin;
use Pengxul\Pay\Plugin\Jsb\AddRadarPlugin;
use Pengxul\Pay\Plugin\Jsb\Pay\Scan\QueryPlugin;
use Pengxul\Pay\Plugin\Jsb\ResponsePlugin;
use Pengxul\Pay\Plugin\Jsb\StartPlugin;
use Pengxul\Pay\Plugin\Jsb\VerifySignaturePlugin;
use Pengxul\Pay\Shortcut\Jsb\QueryShortcut;
use Pengxul\Pay\Tests\TestCase;

class QueryShortcutTest extends TestCase
{

	protected QueryShortcut $plugin;

	protected function setUp(): void
	{
		parent::setUp();

		$this->plugin = new QueryShortcut();
	}

	public function testDefault()
	{
		self::assertEquals([
			StartPlugin::class,
			QueryPlugin::class,
			AddPayloadSignPlugin::class,
			AddRadarPlugin::class,
			VerifySignaturePlugin::class,
			ResponsePlugin::class,
			ParserPlugin::class,
		], $this->plugin->getPlugins([]));
	}
}
