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
use Pengxul\Pay\Plugin\Wechat\V3\Marketing\MchTransfer\CreatePlugin;
use Pengxul\Pay\Plugin\Wechat\V3\VerifySignaturePlugin;
use Pengxul\Pay\Shortcut\Wechat\TransferShortcut;
use Pengxul\Pay\Tests\TestCase;

class TransferShortcutTest extends TestCase
{
    protected TransferShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new TransferShortcut();
    }

    public function testDefault()
    {
        self::assertEquals([
            StartPlugin::class,
            \Pengxul\Pay\Plugin\Wechat\V3\Marketing\Transfer\CreatePlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testMch()
    {
        self::assertEquals([
            StartPlugin::class,
            CreatePlugin::class,
            AddPayloadBodyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'mch_transfer']));
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_SHORTCUT_ACTION_INVALID);

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
