<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use App\Service\SystemConfigService;

class SystemConfigsController extends AbstractController
{
    /**
     * @Inject
     * @var SystemConfigService
     */
    protected $service;

    public function store()
    {
        // $validated = $request->validated();
        $params = $this->request->all();
        $data = $this->service->createData($params);
        return success();
    }
}
