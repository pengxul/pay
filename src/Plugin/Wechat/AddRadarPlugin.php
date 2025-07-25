<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat;

use Closure;
use GuzzleHttp\Psr7\Request;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\get_wechat_body;
use function Pengxul\Pay\get_wechat_method;
use function Pengxul\Pay\get_wechat_url;

class AddRadarPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][AddRadarPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $payload = $rocket->getPayload();
        $config = get_provider_config('wechat', $params);

        $rocket->setRadar(new Request(
            get_wechat_method($payload),
            get_wechat_url($config, $payload),
            $this->getHeaders($payload),
            get_wechat_body($payload),
        ));

        Logger::info('[Wechat][AddRadarPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function getHeaders(?Collection $payload): array
    {
        $headers = [
            'Accept' => 'application/json, text/plain, application/x-gzip',
            'User-Agent' => 'yansongda/pay-v3',
            'Content-Type' => 'application/json; charset=utf-8',
        ];

        // 当 body 里有加密内容时，需要传递此参数用于微信区分
        if (!empty($serialNo = $payload?->get('_serial_no'))) {
            $headers['Wechatpay-Serial'] = $serialNo;
        }

        if (!empty($authorization = $payload?->get('_authorization'))) {
            $headers['Authorization'] = $authorization;
        }

        if (!empty($contentType = $payload?->get('_content_type'))) {
            $headers['Content-Type'] = $contentType;
        }

        if (!empty($accept = $payload?->get('_accept'))) {
            $headers['Accept'] = $accept;
        }

        return $headers;
    }
}
