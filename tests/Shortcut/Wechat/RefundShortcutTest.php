<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Shortcut\Wechat;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Artful\Plugin\StartPlugin;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Wechat\AddRadarPlugin;
use Pengxul\Pay\Plugin\Wechat\ResponsePlugin;
use Pengxul\Pay\Plugin\Wechat\V3\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\App\RefundPlugin as AppRefundPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Combine\RefundPlugin as CombineRefundPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\H5\RefundPlugin as H5RefundPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Jsapi\RefundPlugin as JsapiRefundPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Mini\RefundPlugin as MiniRefundPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Native\RefundPlugin as NativeRefundPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\VerifySignaturePlugin;
use Pengxul\Pay\Shortcut\Wechat\RefundShortcut;
use Pengxul\Pay\Tests\TestCase;

class RefundShortcutTest extends TestCase
{
    protected RefundShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new RefundShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            StartPlugin::class,
            JsapiRefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testApp()
    {
        self::assertEquals([
            StartPlugin::class,
            AppRefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'app']));
    }

    public function testCombine()
    {
        self::assertEquals([
            StartPlugin::class,
            CombineRefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'combine']));
    }

    public function testH5()
    {
        self::assertEquals([
            StartPlugin::class,
            H5RefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'h5']));
    }

    public function testJsapi()
    {
        self::assertEquals([
            StartPlugin::class,
            JsapiRefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'jsapi']));
    }

    public function testMini()
    {
        self::assertEquals([
            StartPlugin::class,
            MiniRefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'mini']));
    }

    public function testNative()
    {
        self::assertEquals([
            StartPlugin::class,
            NativeRefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'native']));
    }


    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_SHORTCUT_ACTION_INVALID);

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
