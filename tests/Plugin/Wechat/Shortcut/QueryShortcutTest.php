<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Plugin\Wechat\Shortcut;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Wechat\Pay\Common\QueryRefundPlugin;
use Pengxul\Pay\Plugin\Wechat\Pay\Common\QueryPlugin;
use Pengxul\Pay\Plugin\Wechat\Shortcut\QueryShortcut;
use Pengxul\Pay\Tests\TestCase;

class QueryShortcutTest extends TestCase
{
    protected QueryShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            QueryPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testRefund()
    {
        self::assertEquals([
            QueryRefundPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'refund']));
    }

    public function testCombine()
    {
        self::assertEquals([
            \Pengxul\Pay\Plugin\Wechat\Pay\Combine\QueryPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'combine']));
    }

    public function testCombineParams()
    {
        self::assertEquals([
            \Pengxul\Pay\Plugin\Wechat\Pay\Combine\QueryPlugin::class,
        ], $this->plugin->getPlugins(['combine_out_trade_no' => '123abc']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_ACTION_ERROR);
        self::expectExceptionMessage('Query action [fooPlugins] not supported');

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
