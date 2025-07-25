<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Marketing\Transfer;

use Closure;
use JetBrains\PhpStorm\Deprecated;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\DecryptException;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Pay;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\encrypt_wechat_contents;
use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\get_wechat_public_key;
use function Pengxul\Pay\get_wechat_serial_no;
use function Pengxul\Pay\get_wechat_type_key;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/batch-transfer-to-balance/transfer-batch/initiate-batch-transfer.html
 */
#[Deprecated(reason: '由于微信支付变更，自 v3.7.12 开始废弃, 并将在 v3.8.0 移除')]
class CreatePlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws DecryptException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][Marketing][Transfer][CreatePlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $payload = $rocket->getPayload();
        $config = get_provider_config('wechat', $params);

        if (Pay::MODE_SERVICE === ($config['mode'] ?? Pay::MODE_NORMAL)) {
            throw new InvalidParamsException(Exception::PARAMS_PLUGIN_ONLY_SUPPORT_NORMAL_MODE, '参数异常: 发起商家转账，只支持普通商户模式，当前配置为服务商模式');
        }

        if (is_null($payload) || $payload->isEmpty()) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 发起商家转账参数，参数缺失');
        }

        $rocket->mergePayload(array_merge(
            [
                '_method' => 'POST',
                '_url' => 'v3/transfer/batches',
                'appid' => $payload->get('appid', $config[get_wechat_type_key($params)] ?? ''),
            ],
            $this->normal($params, $payload)
        ));

        Logger::info('[Wechat][Marketing][Transfer][CreatePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     * @throws DecryptException
     * @throws InvalidConfigException
     */
    protected function normal(array $params, Collection $payload): array
    {
        if (!$payload->has('transfer_detail_list.0.user_name')) {
            return [];
        }

        return $this->encryptSensitiveData($params, $payload);
    }

    /**
     * @throws ContainerException
     * @throws DecryptException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    protected function encryptSensitiveData(array $params, Collection $payload): array
    {
        $data['transfer_detail_list'] = $payload->get('transfer_detail_list', []);
        $data['_serial_no'] = get_wechat_serial_no($params);

        $config = get_provider_config('wechat', $params);
        $publicKey = get_wechat_public_key($config, $data['_serial_no']);

        foreach ($data['transfer_detail_list'] as $key => $list) {
            if (!empty($list['user_name'])) {
                $data['transfer_detail_list'][$key]['user_name'] = encrypt_wechat_contents($list['user_name'], $publicKey);
            }
        }

        return $data;
    }
}
