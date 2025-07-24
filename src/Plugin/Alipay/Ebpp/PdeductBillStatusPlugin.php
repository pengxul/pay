<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\Ebpp;

use Pengxul\Pay\Plugin\Alipay\GeneralPlugin;

/**
 * @see https://opendocs.alipay.com/open/02hd36
 */
class PdeductBillStatusPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.ebpp.pdeduct.bill.pay.status';
    }
}
