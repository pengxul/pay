<?php

declare(strict_types=1);

namespace Pengxul\Pay\Shortcut\Unipay;

use Pengxul\Artful\Contract\ShortcutInterface;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Unipay\AddRadarPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Unipay\Open\Pay\QrCode\CancelPlugin as QrCodeCancelPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\Pay\Web\CancelPlugin as webCancelPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\StartPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\VerifySignaturePlugin;
use Pengxul\Pay\Plugin\Unipay\Qra\AddPayloadSignaturePlugin as QraAddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Unipay\Qra\Pos\CancelPlugin as QraPosCancelQueryPlugin;
use Pengxul\Pay\Plugin\Unipay\Qra\StartPlugin as QraStartPlugin;
use Pengxul\Pay\Plugin\Unipay\Qra\VerifySignaturePlugin as QraVerifySignaturePlugin;
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
        return $this->webPlugins();
    }

    protected function webPlugins(): array
    {
        return [
            StartPlugin::class,
            webCancelPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function qrCodePlugins(): array
    {
        return [
            StartPlugin::class,
            QrCodeCancelPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ParserPlugin::class,
        ];
    }

    protected function qraPosPlugins(): array
    {
        return [
            QraStartPlugin::class,
            QraPosCancelQueryPlugin::class,
            QraAddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            QraVerifySignaturePlugin::class,
            ParserPlugin::class,
        ];
    }
}
