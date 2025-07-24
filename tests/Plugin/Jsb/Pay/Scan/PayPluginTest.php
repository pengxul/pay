<?php

namespace Pengxul\Pay\Tests\Plugin\Jsb\Pay\Scan;

use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Jsb\Pay\Scan\PayPlugin;
use Pengxul\Pay\Tests\TestCase;

class PayPluginTest extends TestCase
{

	protected PayPlugin $plugin;

	protected function setUp(): void
	{
		parent::setUp();

		$this->plugin = new PayPlugin();
	}

	public function testNormal()
	{
		$rocket = (new Rocket())
			->setParams([]);

		$result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
		self::assertStringContainsString('atPay', $result->getPayload()->toJson());
	}
}
