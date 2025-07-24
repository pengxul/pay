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
use Pengxul\Pay\Plugin\Wechat\V2\AddPayloadSignaturePlugin;
use Pengxul\Pay\Plugin\Wechat\V2\Papay\Direct\ApplyPlugin;
use Pengxul\Pay\Plugin\Wechat\V2\Papay\Direct\ContractOrderPlugin;
use Pengxul\Pay\Plugin\Wechat\V2\Papay\Direct\MiniOnlyContractPlugin;
use Pengxul\Pay\Plugin\Wechat\V2\Pay\App\InvokePlugin as AppInvokePlugin;
use Pengxul\Pay\Plugin\Wechat\V2\Pay\Mini\InvokePlugin as MiniInvokePlugin;
use Pengxul\Pay\Plugin\Wechat\V2\VerifySignaturePlugin;
use Pengxul\Pay\Shortcut\Wechat\PapayShortcut;
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
            StartPlugin::class,
            ContractOrderPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            MiniInvokePlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'mini']));

        self::assertEquals([
            StartPlugin::class,
            ContractOrderPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            AppInvokePlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_type' => 'app']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_WECHAT_PAPAY_TYPE_NOT_SUPPORTED);

        $this->plugin->getPlugins(['_type' => 'mp']);
    }

    public function testOrder()
    {
        self::assertEquals([
            StartPlugin::class,
            ContractOrderPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            MiniInvokePlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'order', '_type' => 'mini']));

        self::assertEquals([
            StartPlugin::class,
            ContractOrderPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            AppInvokePlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'order', '_type' => 'app']));
    }

    public function testApply()
    {
        self::assertEquals([
            StartPlugin::class,
            ApplyPlugin::class,
            AddPayloadSignaturePlugin::class,
            AddPayloadBodyPlugin::class,
            AddRadarPlugin::class,
            VerifySignaturePlugin::class,
            ResponsePlugin::class,
            ParserPlugin::class,
        ], $this->plugin->getPlugins(['_action' => 'apply']));
    }

    public function testContract()
    {
        self::assertEquals([
            StartPlugin::class,
            MiniOnlyContractPlugin::class,
            AddPayloadSignaturePlugin::class
        ], $this->plugin->getPlugins(['_action' => 'contract', '_type' => 'mini']));

        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_WECHAT_PAPAY_TYPE_NOT_SUPPORTED);

        $this->plugin->getPlugins(['_action' => 'contract', '_type' => 'app']);
    }

    public function testFoo()
    {
        self::expectException(InvalidParamsException::class);
        self::expectExceptionCode(Exception::PARAMS_SHORTCUT_ACTION_INVALID);

        $this->plugin->getPlugins(['_action' => 'foo']);
    }
}
