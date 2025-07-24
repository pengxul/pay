<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\InvalidResponseException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Supports\Collection;

use function Pengxul\Artful\should_do_http_request;

class ResponsePlugin implements PluginInterface
{
    /**
     * @throws InvalidResponseException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[Alipay][ResponsePlugin] 插件开始装载', ['rocket' => $rocket]);

        $destination = $rocket->getDestination();
        $payload = $rocket->getPayload();
        $resultKey = str_replace('.', '_', $payload->get('method')).'_response';

        if (should_do_http_request($rocket->getDirection()) && $destination instanceof Collection) {
            $sign = $destination->get('sign', '');
            $response = $destination->get($resultKey, $destination->all());

            if (empty($sign) && '10000' !== ($response['code'] ?? 'null')) {
                throw new InvalidResponseException(Exception::RESPONSE_BUSINESS_CODE_WRONG, '支付宝网关响应异常: '.($response['sub_msg'] ?? $response['msg'] ?? '未知错误，请查看支付宝原始响应'), $rocket->getDestination());
            }

            $rocket->setDestination(new Collection(array_merge(
                ['_sign' => $sign],
                $response
            )));
        }

        Logger::info('[Alipay][ResponsePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }
}
