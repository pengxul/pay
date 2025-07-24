<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\Shortcut;

use Pengxul\Pay\Contract\ShortcutInterface;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Alipay\Fund\TransCommonQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\Trade\FastRefundQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\Trade\QueryPlugin;
use Pengxul\Supports\Str;

class QueryShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $typeMethod = Str::camel($params['_action'] ?? 'default').'Plugins';

        if (isset($params['out_request_no'])) {
            return $this->refundPlugins();
        }

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}();
        }

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_ACTION_ERROR, "Query action [{$typeMethod}] not supported");
    }

    protected function defaultPlugins(): array
    {
        return [
            QueryPlugin::class,
        ];
    }

    protected function refundPlugins(): array
    {
        return [
            FastRefundQueryPlugin::class,
        ];
    }

    protected function transferPlugins(): array
    {
        return [
            TransCommonQueryPlugin::class,
        ];
    }
}
