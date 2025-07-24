<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Shortcut\Wechat;

use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\StartPlugin;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Plugin\Wechat\AddRadarPlugin;
use Pengxul\Pay\Plugin\Wechat\ResponsePlugin;
use Pengxul\Pay\Plugin\Wechat\V3\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Combine\ClosePlugin as CombineClosePlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Jsapi\ClosePlugin as JsapiClosePlugin;
use Pengxul\Pay\Plugin\Wechat\V3\VerifySignaturePlugin;
use Pengxul\Pay\Shortcut\Wechat\CloseShortcut;
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
            StartPlugin::class,
            JsapiClosePlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testCombine()
    {
        self::assertEquals([
            StartPlugin::class,
            CombineClosePlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'combine']));
    }

    public function testCombineParams()
    {
        self::assertEquals([
            StartPlugin::class,
            CombineClosePlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['combine_out_trade_no' => '123abc']));

        self::assertEquals([
            StartPlugin::class,
            CombineClosePlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['sub_orders' => '123abc']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_SHORTCUT_ACTION_INVALID);

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
