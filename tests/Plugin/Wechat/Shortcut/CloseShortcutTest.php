<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Plugin\Wechat\Shortcut;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Alipay\Fund\TransCommonQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\Pay\Common\ClosePlugin;
use Pengxul\Pay\Plugin\Wechat\Shortcut\CloseShortcut;
use Pengxul\Pay\Tests\TestCase;

class CloseShortcutTest extends TestCase
{
    protected CloseShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new CloseShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            ClosePlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testCombine()
    {
        self::assertEquals([
            \Pengxul\Pay\Plugin\Wechat\Pay\Combine\ClosePlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'combine']));
    }

    public function testCombineParams()
    {
        self::assertEquals([
            \Pengxul\Pay\Plugin\Wechat\Pay\Combine\ClosePlugin::class,
        ], $this->plugin->getPlugins(['combine_out_trade_no' => '123abc']));

        self::assertEquals([
            \Pengxul\Pay\Plugin\Wechat\Pay\Combine\ClosePlugin::class,
        ], $this->plugin->getPlugins(['sub_orders' => '123abc']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_ACTION_ERROR);
        self::expectExceptionMessage('Query action [fooPlugins] not supported');

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
