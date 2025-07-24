<?php

declare(strict_types=1);

namespace Pengxul\Pay\Service;

use Pengxul\Pay\Contract\ConfigInterface;
use Pengxul\Pay\Contract\LoggerInterface;
use Pengxul\Pay\Contract\ServiceProviderInterface;
use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Exception\ServiceNotFoundException;
use Pengxul\Pay\Pay;
use Pengxul\Supports\Logger;

class LoggerServiceProvider implements ServiceProviderInterface
{
    /**
     * @param mixed $data
     *
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function register($data = null): void
    {
        /* @var ConfigInterface $config */
        $config = Pay::get(ConfigInterface::class);

        if (class_exists(\Monolog\Logger::class) && true === $config->get('logger.enable', false)) {
            $logger = new Logger(array_merge(
                ['identify' => 'yansongda.pay'],
                $config->get('logger', [])
            ));

            Pay::set(LoggerInterface::class, $logger);
        }
    }
}
