<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Shortcut\Unipay;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Unipay\AddRadarPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Unipay\Open\Pay\QrCode\PosPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\Pay\QrCode\PosPreAuthPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\StartPlugin;
use Pengxul\Pay\Plugin\Unipay\Open\VerifySignaturePlugin;
use Pengxul\Pay\Plugin\Unipay\Qra\AddPayloadSignaturePlugin as QraAddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Unipay\Qra\Pos\PayPlugin;
use Pengxul\Pay\Plugin\Unipay\Qra\StartPlugin as QraStartPlugin;
use Pengxul\Pay\Plugin\Unipay\Qra\VerifySignaturePlugin as QraVerifySignaturePlugin;
use Pengxul\Pay\Shortcut\Unipay\PosShortcut;
use Pengxul\Pay\Tests\TestCase;

class PosShortcutTest extends TestCase
{
    protected PosShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new PosShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            StartPlugin::class,
            PosPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testPreAuth()
    {
        self::assertEquals([
            StartPlugin::class,
            PosPreAuthPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'pre_auth']));
    }

    public function testQra()
    {
        self::assertEquals([
            QraStartPlugin::class,
            PayPlugin::class,
            QraAddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            QraVerifySignaturePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'qra']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_SHORTCUT_ACTION_INVALID);

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
