<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Unipay;

use Closure;
use GuzzleHttp\Psr7\Request;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

use function Pengxul\Artful\get_radar_method;
use function Pengxul\Pay\get_provider_config;
use function Pengxul\Pay\get_unipay_body;
use function Pengxul\Pay\get_unipay_url;

class AddRadarPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws InvalidParamsException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Unipay][AddRadarPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('unipay', $params);
        $payload = $rocket->getPayload();

        $rocket->setRadar(new Request(
            get_radar_method($payload) ?? 'POST',
            get_unipay_url($config, $payload),
            $this->getHeaders(),
            get_unipay_body($payload),
        ));

        Logger::info('[Unipay][AddRadarPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function getHeaders(): array
    {
        return [
            'User-Agent' => 'yansongda/pay-v3',
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ];
    }
}
