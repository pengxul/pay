<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Pay\Refund;

use Closure;
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

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/refund/refunds/create-abnormal-refund.html
 * @see https://pay.weixin.qq.com/docs/partner/apis/refund/refunds/create-abnormal-refund.html
 */
class RefundAbnormalPlugin implements PluginInterface
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
        Logger::debug('[Wechat][V3][Pay][Refund][RefundAbnormalPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $payload = $rocket->getPayload();
        $config = get_provider_config('wechat', $params);
        $refundId = $payload?->get('refund_id') ?? null;

        if (empty($refundId)) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 发起异常退款，参数缺少 `refund_id`');
        }

        if (Pay::MODE_SERVICE === ($config['mode'] ?? Pay::MODE_NORMAL)) {
            $data = $this->service($params, $config, $payload);
        }

        $rocket->mergePayload(array_merge(
            [
                '_method' => 'POST',
                '_url' => 'v3/refund/domestic/refunds/'.$refundId.'/apply-abnormal-refund',
                '_service_url' => 'v3/refund/domestic/refunds/'.$refundId.'/apply-abnormal-refund',
            ],
            $data ?? $this->normal($params, $config, $payload)
        ))->exceptPayload('refund_id');

        Logger::info('[Wechat][V3][Pay][Refund][RefundAbnormalPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     * @throws DecryptException
     * @throws InvalidConfigException
     */
    protected function normal(array $params, array $config, Collection $payload): array
    {
        return $this->encryptSensitiveData($params, $config, $payload);
    }

    /**
     * @throws ContainerException
     * @throws DecryptException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    protected function service(array $params, array $config, Collection $payload): array
    {
        $data = [
            'sub_mchid' => $payload->get('sub_mchid', $config['sub_mch_id'] ?? ''),
        ];

        return array_merge($data, $this->encryptSensitiveData($params, $config, $payload));
    }

    /**
     * @throws ContainerException
     * @throws DecryptException
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    protected function encryptSensitiveData(array $params, array $config, Collection $payload): array
    {
        if ($payload->has('bank_account') && $payload->has('real_name')) {
            $data['_serial_no'] = get_wechat_serial_no($params);

            $config = get_provider_config('wechat', $params);
            $publicKey = get_wechat_public_key($config, $data['_serial_no']);

            $data['real_name'] = encrypt_wechat_contents($payload->get('real_name'), $publicKey);
            $data['bank_account'] = encrypt_wechat_contents($payload->get('bank_account'), $publicKey);
        }

        return $data ?? [];
    }
}
