<?php

declare(strict_types=1);

namespace Pengxul\Pay\Service;

use GuzzleHttp\Client;
use Pengxul\Pay\Contract\ConfigInterface;
use Pengxul\Pay\Contract\HttpClientInterface;
use Pengxul\Pay\Contract\ServiceProviderInterface;
use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Exception\ServiceNotFoundException;
use Pengxul\Pay\Pay;
use Pengxul\Supports\Config;

class HttpServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function register($data = null): void
    {
        /* @var Config $config */
        $config = Pay::get(ConfigInterface::class);

        if (class_exists(Client::class)) {
            $service = new Client($config->get('http', []));

            Pay::set(HttpClientInterface::class, $service);
        }
    }
}
