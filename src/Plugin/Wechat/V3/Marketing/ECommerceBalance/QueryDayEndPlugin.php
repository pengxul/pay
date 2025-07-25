<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Marketing\ECommerceBalance;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\ContainerException;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Exception\ServiceNotFoundException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Pay;
use Pengxul\Supports\Collection;

use function Pengxul\Artful\filter_params;
use function Pengxul\Pay\get_provider_config;

/**
 * @see https://pay.weixin.qq.com/docs/partner/apis/ecommerce-balance/accounts/query-day-end-balance.html
 */
class QueryDayEndPlugin implements PluginInterface
{
    /**
     * @throws InvalidParamsException
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][Marketing][ECommerceBalance][QueryDayEndPlugin] 插件开始装载', ['rocket' => $rocket]);

        $config = get_provider_config('wechat', $rocket->getParams());
        $payload = $rocket->getPayload();
        $accountType = $payload?->get('account_type') ?? null;

        if (Pay::MODE_NORMAL === ($config['mode'] ?? Pay::MODE_NORMAL)) {
            throw new InvalidParamsException(Exception::PARAMS_PLUGIN_ONLY_SUPPORT_SERVICE_MODE, '参数异常: 查询电商平台账户日终余额，只支持服务商模式，当前配置为普通商户模式');
        }

        if (empty($accountType)) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 查询电商平台账户日终余额，参数缺少 `account_type`');
        }

        $rocket->setPayload([
            '_method' => 'GET',
            '_service_url' => 'v3/merchant/fund/dayendbalance/'.$accountType.$this->service($payload),
        ]);

        Logger::info('[Wechat][Marketing][ECommerceBalance][QueryDayEndPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function service(Collection $payload): string
    {
        $query = filter_params($payload)->except('account_type');

        if ($query->isEmpty()) {
            return '';
        }

        return '?'.$query->query();
    }
}
