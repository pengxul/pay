<?php

namespace Pengxul\Pay\Tests\Plugin\Jsb;

use Pengxul\Artful\Contract\ConfigInterface;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Jsb\AddPayloadSignPlugin;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;
use Pengxul\Supports\Config;

class AddPayloadSignPluginTest extends TestCase
{
	protected AddPayloadSignPlugin $plugin;

	protected function setUp(): void
	{
		parent::setUp();

		$this->plugin = new AddPayloadSignPlugin();
	}

	public function testSignNormal()
	{
		$payload = ['outTradeNo'=>'YC202406170003','totalFee'=>0.01,'proInfo'=>'充值','backUrl'=>'http:\/\/127.0.0.1:8000\/epay\/return','createData'=>'20240618','createTime'=>'022522','bizDate'=>'20240618','msgId'=>'16253083-49c4-4142-8c56-997accf3d667','svrCode'=>'','partnerId'=>'6a13eab71c4f4b0aa4757eda6fc59710','channelNo'=>'m','publicKeyCode'=>'00','version'=>'v1.0.0','charset'=>'utf-8','service'=>'atPay'];
		$sign = "Bho3LZvuv6wrQUAk6EP5lpGTCf5nDA1KDQwJy5Cog6m9S3UMVqpn0AC8+rrv5va63z5zAC6aQ7qrVH1OQ3hCeEUhhGix5HRUNgs2lzCkpywQnNsjeuapAAmfzVnDfBncPv9HuZSfdGxCOPqlkaSxonSXbB5ZpUfXbH3QjQo2F2w=";
		$rocket = new Rocket();
		$rocket->setParams([])->setPayload(new Collection($payload));

		$result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
		self::assertSame($sign, $result->getPayload()->get('sign'));
	}

	public function testEmptyPayload()
	{
		self::expectException(InvalidParamsException::class);
		self::expectExceptionCode(Exception::PARAMS_NECESSARY_PARAMS_MISSING);
		self::expectExceptionMessage('参数异常: 缺少支付必要参数。可能插件用错顺序，应该先使用 `业务插件`');
		$rocket = new Rocket();
		$rocket->setParams([])->setPayload(new Collection());

		$this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
	}

	public function testMissMchSecretCertPath()
	{
		$payload = ['outTradeNo'=>'YC202406170003','totalFee'=>0.01,'proInfo'=>'充值','backUrl'=>'http:\/\/127.0.0.1:8000\/epay\/return','createData'=>'20240618','createTime'=>'022522','bizDate'=>'20240618','msgId'=>'16253083-49c4-4142-8c56-997accf3d667','svrCode'=>'','partnerId'=>'6a13eab71c4f4b0aa4757eda6fc59710','channelNo'=>'m','publicKeyCode'=>'00','version'=>'v1.0.0','charset'=>'utf-8','service'=>'atPay'];
		$sign = "Bho3LZvuv6wrQUAk6EP5lpGTCf5nDA1KDQwJy5Cog6m9S3UMVqpn0AC8+rrv5va63z5zAC6aQ7qrVH1OQ3hCeEUhhGix5HRUNgs2lzCkpywQnNsjeuapAAmfzVnDfBncPv9HuZSfdGxCOPqlkaSxonSXbB5ZpUfXbH3QjQo2F2w=";
		$rocket = new Rocket();
		$rocket->setParams([])->setPayload(new Collection($payload));

		Pay::set(ConfigInterface::class, new Config());
		self::expectException(InvalidConfigException::class);
		self::expectExceptionCode(Exception::CONFIG_JSB_INVALID);
		self::expectExceptionMessage('配置异常: 缺少配置参数 --  [mch_secret_cert_path]');
		$result = $this->plugin->assembly($rocket, function ($rocket) { return $rocket; });
		self::assertSame($sign, $result->getPayload()->get('sign'));
	}
}
