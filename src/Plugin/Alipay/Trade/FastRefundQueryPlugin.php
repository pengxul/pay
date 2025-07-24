<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\Trade;

use Pengxul\Pay\Plugin\Alipay\GeneralPlugin;

/**
 * @see https://opendocs.alipay.com/open/028sma
 */
class FastRefundQueryPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.trade.fastpay.refund.query';
    }
}
