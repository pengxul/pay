<?php

namespace Pengxul\Pay\Tests\Plugin\Jsb\Pay\Scan;

use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Jsb\Pay\Scan\QueryPlugin;
use Pengxul\Pay\Tests\TestCase;

class QueryPluginTest extends TestCase
{
	protected QueryPlugin $plugin;

	protected function setUp(): void
	{
		parent::setUp();

		$this->plugin = new QueryPlugin();
	}

	public function testNormal()
	{
		$rocket = (new Rocket())
			->setParams([]);

		$result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
		self::assertStringContainsString('payCheck', $result->getPayload()->toJson());
		self::assertStringContainsString('deviceNo', $result->getPayload()->toJson());
	}
}
