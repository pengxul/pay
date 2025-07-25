<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Extend\Complaints;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;

use function Pengxul\Pay\get_provider_config;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/consumer-complaint/complaints/complete-complaint-v2.html
 * @see https://pay.weixin.qq.com/docs/partner/apis/consumer-complaint/complaints/complete-complaint-v2.html
 */
class CompletePlugin implements PluginInterface
{
    /**
     * @throws InvalidParamsException
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][Extend][Complaints][CompletePlugin] 插件开始装载', ['rocket' => $rocket]);

        $config = get_provider_config('wechat', $rocket->getParams());
        $payload = $rocket->getPayload();
        $complaintId = $payload?->get('complaint_id') ?? null;

        if (empty($complaintId)) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 反馈处理完成，参数缺少 `complaint_id`');
        }

        $rocket->setPayload([
            '_method' => 'POST',
            '_url' => 'v3/merchant-service/complaints-v2/'.$complaintId.'/complete',
            '_service_url' => 'v3/merchant-service/complaints-v2/'.$complaintId.'/complete',
            'complainted_mchid' => $payload->get('complainted_mchid') ?? $config['mch_id'],
        ]);

        Logger::info('[Wechat][Extend][Complaints][CompletePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
