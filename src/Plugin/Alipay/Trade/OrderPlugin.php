<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\Trade;

use Pengxul\Pay\Plugin\Alipay\GeneralPlugin;

class OrderPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.trade.order.pay';
    }
}
