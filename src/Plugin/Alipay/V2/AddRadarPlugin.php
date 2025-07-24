<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2;

use Closure;
use GuzzleHttp\Psr7\Request;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Supports\Collection;

use function Pengxul\Artful\get_radar_method;
use function Pengxul\Pay\get_alipay_url;
use function Pengxul\Pay\get_provider_config;

class AddRadarPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][AddRadarPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('alipay', $params);
        $payload = $rocket->getPayload();

        $rocket->setRadar(new Request(
            // 这里因为支付宝的 payload 里不包含 _method，所以需要取 params 中的
            get_radar_method(new Collection($params)) ?? 'POST',
            get_alipay_url($config, $payload),
            $this->getHeaders(),
            // 不能用 packer，支付宝接收的是 x-www-form-urlencoded 返回的又是 json，packer 用的是返回.
            $payload?->query() ?? '',
        ));

        Logger::info('[Alipay][AddRadarPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
            'User-Agent' => 'yansongda/pay-v3',
        ];
    }
}
