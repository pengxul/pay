<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Jsb;

use Closure;
use GuzzleHttp\Psr7\Request;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\get_jsb_url;
use function Pengxul\Pay\get_provider_config;

class AddRadarPlugin implements PluginInterface
{
    /**
     * @throws ServiceNotFoundException
     * @throws ContainerException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::info('[Jsb][AddRadarPlugin] 插件开始装载', ['rocket' => $rocket]);

        $params = $rocket->getParams();
        $config = get_provider_config('jsb', $params);
        $payload = $rocket->getPayload();

        $rocket->setRadar(new Request(
            strtoupper($params['_method'] ?? 'POST'),
            get_jsb_url($config, $payload),
            $this->getHeaders(),
            $this->getBody($payload),
        ));

        Logger::info('[Jsb][AddRadarPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'text/html',
            'User-Agent' => 'yansongda/pay-v3',
        ];
    }

    protected function getBody(Collection $payload): string
    {
        $sign = $payload->get('sign');
        $signType = $payload->get('signType');

        $payload->forget('sign');
        $payload->forget('signType');

        $payload = $payload->sortKeys();

        $payload->set('sign', $sign);
        $payload->set('signType', $signType);

        return $payload->toString();
    }
}
