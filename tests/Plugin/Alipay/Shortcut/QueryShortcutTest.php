<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Plugin\Alipay\Shortcut;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\Alipay\Fund\TransCommonQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\Shortcut\QueryShortcut;
use Pengxul\Pay\Plugin\Alipay\Trade\FastRefundQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\Trade\QueryPlugin;
use Pengxul\Pay\Tests\TestCase;

class QueryShortcutTest extends TestCase
{
    protected $plugin;

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
            FastRefundQueryPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'refund']));
    }

    public function testTransfer()
    {
        self::assertEquals([
            TransCommonQueryPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'transfer']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_ACTION_ERROR);
        self::expectExceptionMessage('Query action [fooPlugins] not supported');

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
