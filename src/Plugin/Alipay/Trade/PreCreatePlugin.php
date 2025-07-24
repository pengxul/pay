<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\Trade;

use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Exception\ServiceNotFoundException;
use Pengxul\Pay\Plugin\Alipay\GeneralPlugin;
use Pengxul\Pay\Rocket;
use Pengxul\Pay\Traits\SupportServiceProviderTrait;

/**
 * @see https://opendocs.alipay.com/open/02ekfg?scene=common
 */
class PreCreatePlugin extends GeneralPlugin
{
    use SupportServiceProviderTrait;

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function doSomethingBefore(Rocket $rocket): void
    {
        $this->loadAlipayServiceProvider($rocket);
    }

    protected function getMethod(): string
    {
        return 'alipay.trade.precreate';
    }
}
