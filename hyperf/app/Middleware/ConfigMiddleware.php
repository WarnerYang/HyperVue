<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Hyperf\Contract\ConfigInterface;
use App\Service\SystemConfigService;

class ConfigMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     * @var SystemConfigService
     */
    protected $systemConfigService;

    /**
     * @Inject
     * @var ConfigInterface
     */
    protected $config;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $data = $this->systemConfigService->getDataList();
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $this->config->set($key, $value);
            }
        }
        return $handler->handle($request);
    }
}
