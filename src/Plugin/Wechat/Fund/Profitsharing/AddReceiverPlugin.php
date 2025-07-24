<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\Fund\Profitsharing;

use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Exception\InvalidConfigException;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Exception\InvalidResponseException;
use Pengxul\Pay\Exception\ServiceNotFoundException;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Plugin\Wechat\GeneralPlugin;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Traits\HasWechatEncryption;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\encrypt_wechat_contents;
use function Pengxul\Pay\get_wechat_config;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter8_1_8.shtml
 */
class AddReceiverPlugin extends GeneralPlugin
{
    use HasWechatEncryption;

    /**
     * @throws ContainerException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws InvalidResponseException
     * @throws ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $params = $rocket->getParams();
        $config = get_wechat_config($rocket->getParams());
        $extra = $this->getWechatId($config, $rocket->getPayload());

        if (!empty($params['name'] ?? '')) {
            $params = $this->loadSerialNo($params);

            $name = $this->getEncryptUserName($params);
            $params['name'] = $name;
            $extra['name'] = $name;
            $rocket->setParams($params);
        }

        $rocket->mergePayload($extra);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/profitsharing/receivers/add';
    }

    protected function getWechatId(array $config, Collection $payload): array
    {
        $wechatId = [
            'appid' => $config['mp_app_id'] ?? null,
        ];

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null)) {
            $wechatId['sub_mchid'] = $payload->get('sub_mchid', $config['sub_mch_id'] ?? '');
        }

        return $wechatId;
    }

    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    protected function getEncryptUserName(array $params): string
    {
        $name = $params['name'] ?? '';
        $publicKey = $this->getPublicKey($params, $params['_serial_no'] ?? '');

        return encrypt_wechat_contents($name, $publicKey);
    }
}
