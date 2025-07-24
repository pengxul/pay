<?php

declare(strict_types=1);

namespace Pengxul\Pay;

use Closure;
use Psr\Container\ContainerInterface;
use Pengxul\Artful\Artful;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Pay\Provider\Alipay;
use Pengxul\Pay\Provider\Douyin;
use Pengxul\Pay\Provider\Jsb;
use Pengxul\Pay\Provider\Unipay;
use Pengxul\Pay\Provider\Wechat;
use Pengxul\Pay\Service\AlipayServiceProvider;
use Pengxul\Pay\Service\DouyinServiceProvider;
use Pengxul\Pay\Service\JsbServiceProvider;
use Pengxul\Pay\Service\UnipayServiceProvider;
use Pengxul\Pay\Service\WechatServiceProvider;

/**
 * @method static Alipay alipay(array $config = [], $container = null)
 * @method static Wechat wechat(array $config = [], $container = null)
 * @method static Unipay unipay(array $config = [], $container = null)
 * @method static Jsb    jsb(array $config = [], $container = null)
 * @method static Douyin douyin(array $config = [], $container = null)
 */
class Pay
{
    /**
     * 正常模式.
     */
    public const MODE_NORMAL = 0;

    /**
     * 沙箱模式.
     */
    public const MODE_SANDBOX = 1;

    /**
     * 服务商模式.
     */
    public const MODE_SERVICE = 2;

    protected static array $providers = [
        AlipayServiceProvider::class,
        WechatServiceProvider::class,
        UnipayServiceProvider::class,
        JsbServiceProvider::class,
        DouyinServiceProvider::class,
    ];

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public static function __callStatic(string $service, array $config = [])
    {
        if (!empty($config)) {
            self::config(...$config);
        }

        return Artful::get($service);
    }

    /**
     * @throws ContainerException
     */
    public static function config(array $config = [], null|Closure|ContainerInterface $container = null): bool
    {
        $result = Artful::config($config, $container);

        foreach (self::$providers as $provider) {
            Artful::load($provider);
        }

        return $result;
    }

    /**
     * @throws ContainerException
     */
    public static function set(string $name, mixed $value): void
    {
        Artful::set($name, $value);
    }

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public static function get(string $service): mixed
    {
        return Artful::get($service);
    }

    public static function setContainer(null|Closure|ContainerInterface $container): void
    {
        Artful::setContainer($container);
    }

    public static function clear(): void
    {
        Artful::clear();
    }
}
