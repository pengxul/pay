<?php

declare(strict_types=1);

namespace Pengxul\Pay\Shortcut\Alipay;

use Pengxul\Artful\Contract\ShortcutInterface;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Alipay\V2\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\AddRadarPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\FormatPayloadBizContentPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Agreement\Pay\ClosePlugin as AgreementClosePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\App\ClosePlugin as AppClosePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Authorization\Pay\ClosePlugin as AuthorizationClosePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\H5\ClosePlugin as H5ClosePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Mini\ClosePlugin as MiniClosePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Pos\ClosePlugin as PosClosePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Scan\ClosePlugin as ScanClosePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Web\ClosePlugin as WebClosePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\ResponsePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\StartPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\VerifySignaturePlugin;
use Pengxul\Supports\Str;

class CloseShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $method = Str::camel($params['_action'] ?? 'default').'Plugins';

        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        throw new InvalidParamsException(Exception::PARAMS_SHORTCUT_ACTION_INVALID, "您所提供的 action 方法 [{$method}] 不支持，请参考文档或源码确认");
    }

    protected function defaultPlugins(): array
    {
        return $this->webPlugins();
    }

    protected function agreementPlugins(): array
    {
        return [
            StartPlugin::class,
            AgreementClosePlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function appPlugins(): array
    {
        return [
            StartPlugin::class,
            AppClosePlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function authorizationPlugins(): array
    {
        return [
            StartPlugin::class,
            AuthorizationClosePlugin::class,
            FormatPayloadBizContentPlugin::class,
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
            MiniClosePlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function posPlugins(): array
    {
        return [
            StartPlugin::class,
            PosClosePlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function scanPlugins(): array
    {
        return [
            StartPlugin::class,
            ScanClosePlugin::class,
            FormatPayloadBizContentPlugin::class,
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
            H5ClosePlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function webPlugins(): array
    {
        return [
            StartPlugin::class,
            WebClosePlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }
}
