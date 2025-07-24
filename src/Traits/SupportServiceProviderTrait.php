<?php

declare(strict_types=1);

namespace Pengxul\Pay\Traits;

use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Exception\ServiceNotFoundException;
use Pengxul\Pay\Pay;
use Pengxul\Pay\Rocket;

use function Pengxul\Pay\get_alipay_config;

trait SupportServiceProviderTrait
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function loadAlipayServiceProvider(Rocket $rocket): void
    {
        $params = $rocket->getParams();
        $config = get_alipay_config($params);
        $serviceProviderId = $config['service_provider_id'] ?? null;

        if (Pay::MODE_SERVICE !== ($config['mode'] ?? Pay::MODE_NORMAL)
            || empty($serviceProviderId)) {
            return;
        }

        $rocket->mergeParams([
            'extend_params' => array_merge($params['extend_params'] ?? [], ['sys_service_provider_id' => $serviceProviderId]),
        ]);
    }
}
