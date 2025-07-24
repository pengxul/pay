<?php

namespace Pengxul\Pay\Tests\Plugin\Jsb;

use GuzzleHttp\Psr7\ServerRequest;
use Pengxul\Artful\Contract\ConfigInterface;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\InvalidResponseException;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidSignException;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Jsb\CallbackPlugin;
use Pengxul\Pay\Tests\TestCase;
use Pengxul\Supports\Collection;
use Pengxul\Supports\Config;

class CallbackPluginTest extends TestCase
{
	protected CallbackPlugin $plugin;

	protected function setUp(): void
	{
		parent::setUp();

		$this->plugin = new CallbackPlugin();
	}

	public function testNormal()
	{
		$payload = [
			'partnerId'=> '6a13eab71c4f4b0aa4757eda6fc59710',
			'orderStatus'=> '1',
			'totalFee'=> '0.02',
			'outTradeNo'=> 'RC240613164110030316',
			'orderNo'=> '20240613164114400729509',
			'field1'=> '2',
			'field2'=> '',
			'field3'=> '20240613164139|20240613164134400800219',
			'signType'=> 'RSA',
			'sign'=> 'DPKX4mZAVd/LwMDOt1OJgryBuPeH78y7B78smze+m+vvzae5MBf0O3BoTvVJQHD/RPVftHVvnYHeKvIjCC2bCrxoY9Sv2N8Hbr5HfjIikk0a2qaIQp6TTvecMP9JitzSuZP+sih+uxMkRM5Nrg8weGbePaQ6nODNWiSGDhV+Jq0='
		];
		$request = new ServerRequest('POST', 'http://localhost');
		$request = $request->withParsedBody($payload);

		$rocket = new Rocket();
		$rocket->setParams(['request'=>Collection::wrap($request->getParsedBody())]);

		$result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket;});

		self::assertNotEmpty($result->getPayload()->all());
	}

	public function testErrorSign()
	{
		self::expectException(InvalidSignException::class);
		self::expectExceptionCode(Exception::SIGN_ERROR);
		$payload = [
			'partnerId'=> '6a13eab71c4f4b0aa4757eda6fc59710',
			'orderStatus'=> '1',
			'totalFee'=> '0.02',
			'outTradeNo'=> 'RC240613164110030315',
			'orderNo'=> '20240613164114400729509',
			'field1'=> '2',
			'field2'=> '',
			'field3'=> '20240613164139|20240613164134400800219',
			'signType'=> 'RSA',
			'sign'=> 'DPKX4mZAVd/LwMDOt1OJgryBuPeH78y7B78smze+m+vvzae5MBf0O3BoTvVJQHD/RPVftHVvnYHeKvIjCC2bCrxoY9Sv2N8Hbr5HfjIikk0a2qaIQp6TTvecMP9JitzSuZP+sih+uxMkRM5Nrg8weGbePaQ6nODNWiSGDhV+Jq0='
		];
		$request = new ServerRequest('POST', 'http://localhost');
		$request = $request->withParsedBody($payload);

		$rocket = new Rocket();
		$rocket->setParams(['request'=>Collection::wrap($request->getParsedBody())]);

		$result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket;});

		self::assertNotEmpty($result->getPayload()->all());
	}

	public function testEmptySign()
	{
		self::expectException(InvalidSignException::class);
		self::expectExceptionCode(Exception::SIGN_EMPTY);
		$payload = [
			'partnerId'=> '6a13eab71c4f4b0aa4757eda6fc59710',
			'orderStatus'=> '1',
			'totalFee'=> '0.02',
			'outTradeNo'=> 'RC240613164110030316',
			'orderNo'=> '20240613164114400729509',
			'field1'=> '2',
			'field2'=> '',
			'field3'=> '20240613164139|20240613164134400800219',
			'signType'=> 'RSA',
			'sign'=> ''
		];
		$request = new ServerRequest('POST', 'http://localhost');
		$request = $request->withParsedBody($payload);

		$rocket = new Rocket();
		$rocket->setParams(['request'=>Collection::wrap($request->getParsedBody())]);

		$result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket;});

		self::assertNotEmpty($result->getPayload()->all());
	}

	public function testErrorRequestType()
	{
		self::expectException(InvalidParamsException::class);
		self::expectExceptionCode(Exception::PARAMS_CALLBACK_REQUEST_INVALID);
		$payload = [
		];
		$request = new ServerRequest('POST', 'http://localhost');
		$request = $request->withParsedBody($payload);

		$rocket = new Rocket();
		$rocket->setParams(['request'=>$request->getParsedBody()]);

		$result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket;});

		self::assertNotEmpty($result->getPayload()->all());
	}

	public function testMissingEpayPublicCertPath()
	{
		self::expectException(InvalidConfigException::class);
		self::expectExceptionCode(Exception::CONFIG_JSB_INVALID);
		Pay::set(ConfigInterface::class, new Config());
		$payload = [
			'partnerId'=> '6a13eab71c4f4b0aa4757eda6fc59710',
			'orderStatus'=> '1',
			'totalFee'=> '0.02',
			'outTradeNo'=> 'RC240613164110030316',
			'orderNo'=> '20240613164114400729509',
			'field1'=> '2',
			'field2'=> '',
			'field3'=> '20240613164139|20240613164134400800219',
			'signType'=> 'RSA',
			'sign'=> 'DPKX4mZAVd/LwMDOt1OJgryBuPeH78y7B78smze+m+vvzae5MBf0O3BoTvVJQHD/RPVftHVvnYHeKvIjCC2bCrxoY9Sv2N8Hbr5HfjIikk0a2qaIQp6TTvecMP9JitzSuZP+sih+uxMkRM5Nrg8weGbePaQ6nODNWiSGDhV+Jq0='
		];
		$request = new ServerRequest('POST', 'http://localhost');
		$request = $request->withParsedBody($payload);

		$rocket = new Rocket();
		$rocket->setParams(['request'=>Collection::wrap($request->getParsedBody())]);

		$result = $this->plugin->assembly($rocket, function ($rocket) {return $rocket;});

		self::assertNotEmpty($result->getPayload()->all());
	}
}
