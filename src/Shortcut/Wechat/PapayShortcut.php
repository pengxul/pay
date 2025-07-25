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
use Pengxul\Pay\Plugin\Wechat\V2\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Wechat\V2\Papay\Direct\ApplyPlugin;
use Pengxul\Pay\Plugin\Wechat\V2\Papay\Direct\ContractOrderPlugin;
use Pengxul\Pay\Plugin\Wechat\V2\Papay\Direct\MiniOnlyContractPlugin;
use Pengxul\Pay\Plugin\Wechat\V2\Pay\App\InvokePlugin as AppInvokePlugin;
use Pengxul\Pay\Plugin\Wechat\V2\Pay\Mini\InvokePlugin as MiniInvokePlugin;
use Pengxul\Pay\Plugin\Wechat\V2\VerifySignaturePlugin;
use Pengxul\Supports\Str;

class PapayShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $method = Str::camel($params['_action'] ?? 'default').'Plugins';

        if (method_exists($this, $method)) {
            return $this->{$method}($params);
        }

        throw new InvalidParamsException(Exception::PARAMS_SHORTCUT_ACTION_INVALID, "您所提供的 action 方法 [{$method}] 不支持，请参考文档或源码确认");
    }

    /**
     * @throws InvalidParamsException
     */
    protected function defaultPlugins(array $params): array
    {
        return $this->orderPlugins($params);
    }

    /**
     * @throws InvalidParamsException
     */
    protected function orderPlugins(array $params): array
    {
        return [
            StartPlugin::class,
            ContractOrderPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            $this->getInvoke($params),
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    /**
     * @throws InvalidParamsException
     */
    protected function contractPlugins(array $params): array
    {
        return match ($params['_type'] ?? 'default') {
            'mini' => [StartPlugin::class, MiniOnlyContractPlugin::class, AddPayloadSignaturePlugin::class],
            default => throw new InvalidParamsException(Exception::PARAMS_WECHAT_PAPAY_TYPE_NOT_SUPPORTED, '参数异常: 微信扣关服务纯签约，当前传递的 `_type` 类型不支持')
        };
    }

    protected function applyPlugins(): array
    {
        return [
            StartPlugin::class,
            ApplyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ];
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getInvoke(array $params): string
    {
        return match ($params['_type'] ?? 'default') {
            'app' => AppInvokePlugin::class,
            'mini' => MiniInvokePlugin::class,
            default => throw new InvalidParamsException(Exception::PARAMS_WECHAT_PAPAY_TYPE_NOT_SUPPORTED, '参数异常: 微信扣关服务支付中签约，当前传递的 `_type` 类型不支持')
        };
    }
}
