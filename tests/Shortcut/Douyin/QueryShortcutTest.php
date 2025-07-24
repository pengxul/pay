<?php

declare(strict_types=1);

namespace Pengxul\Pay\Tests\Shortcut\Douyin;

use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Plugin\AddPayloadBodyPlugin;
use Pengxul\Artful\Plugin\ParserPlugin;
use Pengxul\Artful\Plugin\StartPlugin;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\AddRadarPlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\Mini\QueryPlugin as MiniQueryPlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\Mini\QueryRefundPlugin as MiniQueryRefundPlugin;
use Pengxul\Pay\Plugin\Douyin\V1\Pay\ResponsePlugin;
use Pengxul\Pay\Shortcut\Douyin\QueryShortcut;
use Pengxul\Pay\Tests\TestCase;

class QueryShortcutTest extends TestCase
{
    protected QueryShortcut $plugin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new QueryShortcut();
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
            MiniQueryPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins([]));
    }

    public function testRefund()
    {
        self::assertEquals([
            StartPlugin::class,
            MiniQueryRefundPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'refund']));
    }

    public function testMini()
    {
        self::assertEquals([
            StartPlugin::class,
            MiniQueryPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'mini']));
    }

    public function testRefundMini()
    {
        self::assertEquals([
            StartPlugin::class,
            MiniQueryRefundPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'refund_mini']));
    }
}
