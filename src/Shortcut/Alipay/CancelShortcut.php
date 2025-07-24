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
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Agreement\Pay\CancelPlugin as AgreementCancelPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Authorization\Auth\CancelPlugin as AuthorizationCancelPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Mini\CancelPlugin as MiniCancelPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Pos\CancelPlugin as PosCancelPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Scan\CancelPlugin as ScanCancelPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\ResponsePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\StartPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\VerifySignaturePlugin;
use Pengxul\Supports\Str;

class CancelShortcut implements ShortcutInterface
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
        return $this->posPlugins();
    }

    protected function agreementPlugins(): array
    {
        return [
            StartPlugin::class,
            AgreementCancelPlugin::class,
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
            AuthorizationCancelPlugin::class,
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
            MiniCancelPlugin::class,
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
            PosCancelPlugin::class,
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
            ScanCancelPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }
}
