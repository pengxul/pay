<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Unipay\Shortcut;

use Pengxul\Pay\Contract\ShortcutInterface;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Unipay\QrCode\PosNormalPlugin;
use Pengxul\Pay\Plugin\Unipay\QrCode\PosPreAuthPlugin;
use Pengxul\Supports\Str;

class PosShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $typeMethod = Str::camel($params['_action'] ?? 'default').'Plugins';

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}();
        }

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_ACTION_ERROR, "Pos action [{$typeMethod}] not supported");
    }

    protected function defaultPlugins(): array
    {
        return [
            PosNormalPlugin::class,
        ];
    }

    protected function preAuthPlugins(): array
    {
        return [
            PosPreAuthPlugin::class,
        ];
    }
}
