<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Alipay\V2\Pay\Authorization\Pay;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Traits\SupportServiceProviderTrait;

/**
 * @see https://opendocs.alipay.com/open/064jhk?pathHash=1c57dd00&ref=api&scene=32f92b62c19b44cfaf3bf4d974fcbcf3
 */
class PayPlugin implements PluginInterface
{
    use SupportServiceProviderTrait;

    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Alipay][Pay][Authorization][Pay][PayPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->loadAlipayServiceProvider($rocket);

        $rocket->mergePayload([
            'method' => 'alipay.trade.pay',
            'biz_content' => array_merge(
                [
                    'product_code' => 'PREAUTH_PAY',
                ],
                $rocket->getParams(),
            ),
        ]);

        Logger::info('[Alipay][Pay][Authorization][Pay][PayPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
