<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Extend\Complaints;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidConfigException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\decrypt_wechat_contents;
use function Pengxul\Pay\get_provider_config;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/consumer-complaint/complaints/query-complaint-v2.html
 * @see https://pay.weixin.qq.com/docs/partner/apis/consumer-complaint/complaints/query-complaint-v2.html
 */
class QueryDetailPlugin implements PluginInterface
{
    /**
     * @throws InvalidParamsException
     * @throws InvalidConfigException
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][Extend][Complaints][QueryDetailPlugin] 插件开始装载', ['rocket' => $rocket]);

        $complaintId = $rocket->getPayload()?->get('complaint_id') ?? null;

        if (empty($complaintId)) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 查询投诉单详情，参数缺少 `complaint_id`');
        }

        $rocket->setPayload([
            '_method' => 'GET',
            '_url' => 'v3/merchant-service/complaints-v2/'.$complaintId,
            '_service_url' => 'v3/merchant-service/complaints-v2/'.$complaintId,
        ]);

        Logger::info('[Wechat][Extend][Complaints][QueryDetailPlugin] 插件装载完毕', ['rocket' => $rocket]);

        /** @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[Wechat][Extend][Complaints][QueryDetailPlugin] 插件开始后置装载', ['rocket' => $rocket]);

        $destination = $rocket->getDestination();

        if ($destination instanceof Collection && !empty($payerPhone = $destination->get('payer_phone'))) {
            $decryptPayerPhone = decrypt_wechat_contents($payerPhone, get_provider_config('wechat', $rocket->getParams()));

            if (empty($decryptPayerPhone)) {
                throw new InvalidConfigException(Exception::DECRYPT_WECHAT_ENCRYPTED_CONTENTS_INVALID, '参数异常: 查询投诉单详情，参数 `payer_phone` 解密失败');
            }

            $destination->set('payer_phone', $decryptPayerPhone);
        }

        Logger::debug('[Wechat][Extend][Complaints][QueryDetailPlugin] 插件后置装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }
}
