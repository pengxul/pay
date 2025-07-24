<?php

namespace Pengxul\Pay\Tests\Shortcut\Alipay;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Alipay\V2\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\AddRadarPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\FormatPayloadBizContentPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Fund\Transfer\Fund\QueryPlugin as TransferQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Agreement\Pay\QueryPlugin as AgreementQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\App\QueryPlugin as AppQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\App\QueryRefundPlugin as AppQueryRefundPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Authorization\Pay\QueryPlugin as AuthorizationQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Authorization\Pay\QueryRefundPlugin as AuthorizationQueryRefundPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Face\QueryPlugin as FaceQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\H5\QueryPlugin as WapQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\H5\QueryRefundPlugin as WapQueryRefundPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Mini\QueryPlugin as MiniQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Mini\QueryRefundPlugin as MiniQueryRefundPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Pos\QueryPlugin as PosQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Pos\QueryRefundPlugin as PosQueryRefundPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Scan\QueryPlugin as ScanQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Scan\QueryRefundPlugin as ScanQueryRefundPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Web\QueryPlugin as WebQueryPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\Pay\Web\QueryRefundPlugin as WebQueryRefundPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\ResponsePlugin;
use Pengxul\Pay\Plugin\Alipay\V2\StartPlugin;
use Pengxul\Pay\Plugin\Alipay\V2\VerifySignaturePlugin;
use Pengxul\Pay\Shortcut\Alipay\QueryShortcut;
use Pengxul\Pay\Tests\TestCase;

class QueryShortcutTest extends TestCase
{
    protected QueryShortcut $shortcut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->shortcut = new QueryShortcut();
    }

    public function testFooParam()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_SHORTCUT_ACTION_INVALID);

        $this->shortcut->getPlugins(['_action' => 'foo']);
    }

    public function testDefault()
    {
        $result = $this->shortcut->getPlugins([]);

        self::assertEquals([
            StartPlugin::class,
            WebQueryPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testAgreement()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'agreement']);

        self::assertEquals([
            StartPlugin::class,
            AgreementQueryPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testApp()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'app']);

        self::assertEquals([
            StartPlugin::class,
            AppQueryPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testAuthorization()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'authorization']);

        self::assertEquals([
            StartPlugin::class,
            AuthorizationQueryPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testFace()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'face']);

        self::assertEquals([
            StartPlugin::class,
            FaceQueryPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testMini()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'mini']);

        self::assertEquals([
            StartPlugin::class,
            MiniQueryPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testPos()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'pos']);

        self::assertEquals([
            StartPlugin::class,
            PosQueryPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testScan()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'scan']);

        self::assertEquals([
            StartPlugin::class,
            ScanQueryPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testH5()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'h5']);

        self::assertEquals([
            StartPlugin::class,
            WapQueryPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testWeb()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'web']);

        self::assertEquals([
            StartPlugin::class,
            WebQueryPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testTransfer()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'transfer']);

        self::assertEquals([
            StartPlugin::class,
            TransferQueryPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testRefund()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'refund']);

        self::assertEquals([
            StartPlugin::class,
            WebQueryRefundPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testRefundApp()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'refund_app']);

        self::assertEquals([
            StartPlugin::class,
            AppQueryRefundPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testRefundAuthorization()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'refund_authorization']);

        self::assertEquals([
            StartPlugin::class,
            AuthorizationQueryRefundPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testRefundMini()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'refund_mini']);

        self::assertEquals([
            StartPlugin::class,
            MiniQueryRefundPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testRefundPos()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'refund_pos']);

        self::assertEquals([
            StartPlugin::class,
            PosQueryRefundPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testRefundScan()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'refund_scan']);

        self::assertEquals([
            StartPlugin::class,
            ScanQueryRefundPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testRefundH5()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'refund_h5']);

        self::assertEquals([
            StartPlugin::class,
            WapQueryRefundPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }

    public function testRefundWeb()
    {
        $result = $this->shortcut->getPlugins(['_action' => 'refund_web']);

        self::assertEquals([
            StartPlugin::class,
            WebQueryRefundPlugin::class,
            FormatPayloadBizContentPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $result);
    }
}
