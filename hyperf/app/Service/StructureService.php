<?php

declare(strict_types=1);

namespace App\Service;

use Hyperf\Di\Annotation\Inject;
use App\Model\AdminStructure;
use App\Kernel\Category;

class StructureService extends Service
{
    /**
     * @Inject
     * @var AdminStructure
     */
    protected $model;

    public function getDataList()
    {
        $cat = new Category($this->model, ['id', 'pid', 'name', 'title']);
        $data = $cat->getList([], 0, 'id');
        return $data;
    }
}
