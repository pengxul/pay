# 💤快捷方式

Shortcut 即快捷方式，是一系列 Plugin 的组合，方便我们使用 Pay。

## 定义

```php
<?php

declare(strict_types=1);

namespace Pengxul\Pay\Contract;

interface ShortcutInterface
{
    /**
     * @author yansongda <me@yansongda.cn>
     *
     * @return \Pengxul\Pay\Contract\PluginInterface[]|string[]
     */
    public function getPlugins(array $params): array;
}
```

## 详细说明

以我们刚刚在 [插件Plugin](/docs/v3/kernel/plugin.md) 中的例子来说明，
支付宝电脑支付，其实也是一种 快捷方式

```php
<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\Shortcut;

use Pengxul\Pay\Contract\ShortcutInterface;
use Pengxul\Pay\Plugin\Alipay\HtmlResponsePlugin;
use Pengxul\Pay\Plugin\Alipay\Trade\PagePayPlugin;

class WebShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PagePayPlugin::class,
            HtmlResponsePlugin::class,
        ];
    }
}
```

是不是灰常简单？
