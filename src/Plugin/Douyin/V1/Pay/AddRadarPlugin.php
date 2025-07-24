<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Douyin\V1\Pay;

use Closure;
use GuzzleHttp\Psr7\Request;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;

use function Pengxul\Artful\get_radar_body;
use function Pengxul\Artful\get_radar_method;
use function Pengxul\Pay\get_douyin_url;
use function Pengxul\Pay\get_provider_config;

class AddRadarPlugin implements PluginInterface
{
    /**
     * @throws ContainerException
     * @throws InvalidParamsException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Douyin][V1][Pay][AddRadarPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $payload = $rocket->getPayload();
        $config = get_provider_config('douyin', $params);

        $rocket->setRadar(new Request(
            get_radar_method($payload),
            get_douyin_url($config, $payload),
            $this->getHeaders(),
            get_radar_body($payload),
        ));

        Logger::info('[Douyin][V1][Pay][AddRadarPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function getHeaders(): array
    {
        return [
            'User-Agent' => 'yansongda/pay-v3',
            'Content-Type' => 'application/json; charset=utf-8',
        ];
    }
}
