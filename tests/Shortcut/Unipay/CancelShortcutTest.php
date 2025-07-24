<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Shortcut\Unipay;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Unipay\AddRadarPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Unipay\Open\Pay\QrCode\CancelPlugin as QrCodeCancelPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\Pay\Web\CancelPlugin as OnlineGatewayCancelPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\StartPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\VerifySignaturePlugin;
use Pengxul\Pay\Plugin\Unipay\Qra\AddPayloadSignaturePlugin as QraAddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Unipay\Qra\Pos\CancelPlugin as QraPosCancelQueryPlugin;
use Pengxul\Pay\Plugin\Unipay\Qra\StartPlugin as QraStartPlugin;
use Pengxul\Pay\Plugin\Unipay\Qra\VerifySignaturePlugin as QraVerifySignaturePlugin;
use Pengxul\Pay\Shortcut\Unipay\CancelShortcut;
use Pengxul\Pay\Tests\TestCase;

class CancelShortcutTest extends TestCase
{
    protected CancelShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new CancelShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            StartPlugin::class,
            OnlineGatewayCancelPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testQrCode()
    {
        self::assertEquals([
            StartPlugin::class,
            QrCodeCancelPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'qr_code']));
    }

    public function testQraPos()
    {
        self::assertEquals([
            QraStartPlugin::class,
            QraPosCancelQueryPlugin::class,
            QraAddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            QraVerifySignaturePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'qra_pos']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_SHORTCUT_ACTION_INVALID);

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
