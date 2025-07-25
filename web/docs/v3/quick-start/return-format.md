# 返回格式

通过 `yansongda/pay` 调用任何方法最终返回的格式与 `yansongda/artful` 一致
（因为 Pay 是基于 `yansongda/artful` 的），具体来说就是以下三种：

- `\Psr\Http\Message\MessageInterface`
- `\Pengxul\Supports\Collection`
- `\Pengxul\Artful\Rocket`

其中 `\Psr\Http\Message\MessageInterface` 最终 实例/接口 为

- `\GuzzleHttp\Psr7\Response`

:::tip
至于最终返回的到底是什么类型，和不同的方法而定
:::

## MessageInterface

### `\GuzzleHttp\Psr7\Response`

支付宝中

- `app()` APP 支付
- `web()` web 支付
- `h5()`  h5 支付
- `success()` 响应回调

微信中

- `success()` 响应回调

均返回此类，在支持 PSR7 的框架中均可直接返回响应请求

:::tip Laravel 框架
laravel 框架中，自行安装 `symfony/psr-http-message-bridge` 即可支持返回相关响应数据
:::

:::warning ThinkPHP 框架
ThinkPHP 框架在 [https://github.com/top-think/framework/pull/2614](https://github.com/top-think/framework/pull/2614) 之后才支持 PSR7 规范，因此，之前的版本需要参考此 PR 自行解包进行处理返回数据
:::

## Collection

默认情况下，支付宝、微信、银联所有 API 调用场景下绝大多数方法最终都返回的是 `Collection` 实例。
例如常用的「退款」「转账」「小程序支付」等。

`Collection` 类提供了常用的快捷方法，具体 API 可参考源代码 [yansongda/supports](https://github.com/yansongda/supports)

## Rocket

一般情况下，此类不会返回，如果您有自定义的需求，可以在入参时传递 _return_rocket = true 即可。

```php
$params = [
    '_return_rocket' => true,
];

Pay::config($config);

$rocket = Pay::alipay()->app($params);
```

## array

API 调用场景下的返回类型，默认情况下均返回 `Collection` 实例。

如果想返回 array 类型的数据，只需要

```php
$collection->toArray();
```

是不是很简单方便？
