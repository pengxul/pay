<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\V3\Marketing\Fapiao;

use Closure;
use Pengxul\Artful\Contract\PluginInterface;
use Pengxul\Artful\Exception\InvalidParamsException;
use Pengxul\Artful\Logger;
use Pengxul\Artful\Rocket;
use Pengxul\Pay\Exception\Exception;

use function Pengxul\Artful\filter_params;

/**
 * @see https://pay.weixin.qq.com/docs/merchant/apis/fapiao/user-title/get-user-title.html
 */
class QueryUserTitlePlugin implements PluginInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[Wechat][V3][Marketing][Fapiao][QueryUserTitlePlugin] 插件开始装载', ['rocket' => $rocket]);

        $payload = $rocket->getPayload();

        if (empty($payload)) {
            throw new InvalidParamsException(Exception::PARAMS_NECESSARY_PARAMS_MISSING, '参数异常: 获取用户填写的抬头，缺少必要参数');
        }

        $rocket->setPayload([
            '_method' => 'GET',
            '_url' => 'v3/new-tax-control-fapiao/user-title?'.filter_params($payload)->query(),
        ]);

        Logger::info('[Wechat][V3][Marketing][Fapiao][QueryUserTitlePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
