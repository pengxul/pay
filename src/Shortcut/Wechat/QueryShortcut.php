<?php

declare(strict_types=1);

namespace Pengxul\Pay\Shortcut\Wechat;

use Pengxul\Artful\Contract\ShortcutInterface;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Artful\Plugin\StartPlugin;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Wechat\AddRadarPlugin;
use Pengxul\Pay\Plugin\Wechat\ResponsePlugin;
use Pengxul\Pay\Plugin\Wechat\V3\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\MchTransfer\QueryByWxPlugin as MchTransferQueryByWxPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\MchTransfer\QueryPlugin as MchTransferQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\Transfer\Detail\QueryPlugin as TransferQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\App\QueryPlugin as AppQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\App\QueryRefundPlugin as AppQueryRefundPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Combine\QueryPlugin as CombineQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Combine\QueryRefundPlugin as CombineQueryRefundPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\H5\QueryPlugin as H5QueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\H5\QueryRefundPlugin as H5QueryRefundPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Jsapi\QueryPlugin as JsapiQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Jsapi\QueryRefundPlugin as JsapiQueryRefundPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Mini\QueryPlugin as MiniQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Mini\QueryRefundPlugin as MiniQueryRefundPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Native\QueryPlugin as NativeQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Native\QueryRefundPlugin as NativeQueryRefundPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\VerifySignaturePlugin;
use Pengxul\Supports\Str;

class QueryShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        if (isset($params['combine_out_trade_no'])) {
            return $this->combinePlugins();
        }

        $method = Str::camel($params['_action'] ?? 'default').'Plugins';

        if (method_exists($this, $method)) {
            return $this->{$method}($params);
        }

        throw new InvalidParamsException(Exception::PARAMS_SHORTCUT_ACTION_INVALID, "您所提供的 action 方法 [{$method}] 不支持，请参考文档或源码确认");
    }

    protected function defaultPlugins(): array
    {
        return $this->jsapiPlugins();
    }

    protected function appPlugins(): array
    {
        return [
            StartPlugin::class,
            AppQueryPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function combinePlugins(): array
    {
        return [
            StartPlugin::class,
            CombineQueryPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function h5Plugins(): array
    {
        return [
            StartPlugin::class,
            H5QueryPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function jsapiPlugins(): array
    {
        return [
            StartPlugin::class,
            JsapiQueryPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function miniPlugins(): array
    {
        return [
            StartPlugin::class,
            MiniQueryPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function nativePlugins(): array
    {
        return [
            StartPlugin::class,
            NativeQueryPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function mchTransferPlugins(array $params): array
    {
        $query = MchTransferQueryPlugin::class;

        if (isset($params['transfer_bill_no'])) {
            $query = MchTransferQueryByWxPlugin::class;
        }

        return [
            StartPlugin::class,
            $query,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function refundPlugins(): array
    {
        return $this->refundJsapiPlugins();
    }

    protected function refundAppPlugins(): array
    {
        return [
            StartPlugin::class,
            AppQueryRefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function refundCombinePlugins(): array
    {
        return [
            StartPlugin::class,
            CombineQueryRefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function refundH5Plugins(): array
    {
        return [
            StartPlugin::class,
            H5QueryRefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function refundJsapiPlugins(): array
    {
        return [
            StartPlugin::class,
            JsapiQueryRefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function refundMiniPlugins(): array
    {
        return [
            StartPlugin::class,
            MiniQueryRefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function refundNativePlugins(): array
    {
        return [
            StartPlugin::class,
            NativeQueryRefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function transferPlugins(): array
    {
        return [
            StartPlugin::class,
            TransferQueryPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }
}
