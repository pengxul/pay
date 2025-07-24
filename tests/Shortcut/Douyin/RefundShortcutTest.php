<?php

declare(strict_types=1);

namespace Shortcut\Douyin;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Artful\Plugin\StartPlugin;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\AddRadarPlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\Mini\RefundPlugin as MiniRefundPlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\ResponsePlugin;
use Pengxul\Pay\Shortcut\Douyin\RefundShortcut;
use Pengxul\Pay\Tests\TestCase;

class RefundShortcutTest extends TestCase
{
    protected RefundShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new RefundShortcut();
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_SHORTCUT_ACTION_INVALID);

        $this->plugin->getPlugins(['_action' => 'foo']);
    }

    public function testDefault()
    {
        self::assertEquals([
            StartPlugin::class,
            MiniRefundPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testMini()
    {
        self::assertEquals([
            StartPlugin::class,
            MiniRefundPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'mini']));
    }
}
