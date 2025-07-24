<?php

declare(strict_types=1);

namespace Pengxul\Pay\Plugin\Wechat\Risk\Complaints;

use Pengxul\Pay\Direction\OriginResponseDirection;
use Pengxul\Pay\Exception\ContainerException;
use Pengxul\Pay\Exception\Exception;
use Pengxul\Pay\Exception\InvalidParamsException;
use Pengxul\Pay\Exception\ServiceNotFoundException;
use Pengxul\Pay\Plugin\Wechat\GeneralPlugin;
use Pengxul\Pay\Rocket;
use Pengxul\Supports\Collection;

use function Pengxul\Pay\get_wechat_config;

/**
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter10_2_15.shtml
 */
class CompleteComplaintPlugin extends GeneralPlugin
{
    /**
     * @throws ContainerException
     * @throws ServiceNotFoundException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setDirection(OriginResponseDirection::class);

        $payload = $rocket->getPayload();
        $config = get_wechat_config($rocket->getParams());

        $rocket->setPayload(new Collection([
            'complainted_mchid' => $payload->get('complainted_mchid', $config['mch_id'] ?? ''),
        ]));
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('complaint_id')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/merchant-service/complaints-v2/'.
            $payload->get('complaint_id').
            '/complete';
    }
}
