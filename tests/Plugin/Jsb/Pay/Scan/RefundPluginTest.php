<?php

namespace Pengxul\Pay\Tests\Plugin\Jsb\Pay\Scan;

use Pengxul\Artful\Rocket;
use Pengxul\Pay\Plugin\Jsb\Pay\Scan\RefundPlugin;
use Pengxul\Pay\Tests\TestCase;

class RefundPluginTest extends TestCase
{
	protected RefundPlugin $plugin;

	protected function setUp(): void
	{
		parent::setUp();

		$this->plugin = new RefundPlugin();
	}

	public function testNormal()
	{
		$rocket = (new Rocket())
			->setParams([]);

		$result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
		self::assertStringContainsString('payRefund', $result->getPayload()->toJson());
		self::assertStringContainsString('deviceNo', $result->getPayload()->toJson());
	}
}
