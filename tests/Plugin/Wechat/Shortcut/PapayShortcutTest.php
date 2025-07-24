<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Plugin\Wechat\Shortcut;

use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Plugin\ParserPlugin;
use Pengxul\Pay\Plugin\Wechat\Papay\ApplyPlugin;
use Pengxul\Pay\Plugin\Wechat\Papay\ContractOrderPlugin;
use Pengxul\Pay\Plugin\Wechat\Papay\OnlyContractPlugin;
use Pengxul\Pay\Plugin\Wechat\Pay\Common\InvokePrepayV2Plugin;
use Pengxul\Pay\Plugin\Wechat\PreparePlugin;
use Pengxul\Pay\Plugin\Wechat\RadarSignPlugin;
use Pengxul\Pay\Plugin\Wechat\Shortcut\PapayShortcut;
use Pengxul\Pay\Tests\TestCase;

class PapayShortcutTest extends TestCase
{
    protected PapayShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PapayShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            PreparePlugin::class,
            ContractOrderPlugin::class,
            RadarSignPlugin::class,
            InvokePrepayV2Plugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testDefaultMini()
    {
        self::assertEquals([
            PreparePlugin::class,
            ContractOrderPlugin::class,
            RadarSignPlugin::class,
            \Pengxul\Pay\Plugin\Wechat\Pay\Mini\InvokePrepayV2Plugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'mini']));
    }

    public function testDefaultApp()
    {
        self::assertEquals([
            PreparePlugin::class,
            ContractOrderPlugin::class,
            RadarSignPlugin::class,
            \Pengxul\Pay\Plugin\Wechat\Pay\App\InvokePrepayV2Plugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'app']));
    }

    public function testContract()
    {
        self::assertEquals([
            PreparePlugin::class,
            OnlyContractPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'contract']));
    }

    public function testApply()
    {
        self::assertEquals([
            PreparePlugin::class,
            ApplyPlugin::class,
            RadarSignPlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'apply']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::SHORTCUT_MULTI_ACTION_ERROR);
        self::expectExceptionMessage('Papay action [fooPlugins] not supported');

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
