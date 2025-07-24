<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\Fund;

use Pengxul\Pay\Plugin\Alipay\GeneralPlugin;

class TransTobankTransferPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.fund.trans.tobank.transfer';
    }
}
