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
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\MchTransfer\QueryByWxPlugin as MchTransferQueryByWxPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\MchTransfer\QueryPlugin as MchTransferQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\Transfer\Detail\QueryPlugin as TransferQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\App\QueryPlugin as AppQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Combine\QueryPlugin as CombineQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\H5\QueryPlugin as H5QueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Jsapi\QueryPlugin as JsapiQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Jsapi\QueryRefundPlugin as JsapiQueryRefundPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Mini\QueryPlugin as MiniQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\Pay\Native\QueryPlugin as NativeQueryPlugin;
use Pengxul\Pay\Plugin\Wechat\V3\VerifySignaturePlugin;
use Pengxul\Pay\Shortcut\Wechat\QueryShortcut;
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
            StartPlugin::class,
            JsapiQueryPlugin::class,
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
            AppQueryPlugin::class,
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
            CombineQueryPlugin::class,
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
            CombineQueryPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['combine_out_trade_no' => '123abc']));
    }

    public function testH5()
    {
        self::assertEquals([
            StartPlugin::class,
            H5QueryPlugin::class,
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
            JsapiQueryPlugin::class,
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
            MiniQueryPlugin::class,
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
            NativeQueryPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'native']));
    }

    public function testRefund()
    {
        self::assertEquals([
            StartPlugin::class,
            JsapiQueryRefundPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'refund']));
    }

    public function testTransfer()
    {
        self::assertEquals([
            StartPlugin::class,
            TransferQueryPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'transfer']));
    }

    public function testMchTransfer()
    {
        self::assertEquals([
            StartPlugin::class,
            MchTransferQueryPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'mch_transfer']));

        self::assertEquals([
            StartPlugin::class,
            MchTransferQueryByWxPlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'mch_transfer', 'transfer_bill_no' => '123']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_SHORTCUT_ACTION_INVALID);

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}