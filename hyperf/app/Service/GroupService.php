<?php

declare(strict_types=1);

namespace App\Service;

use Hyperf\Di\Annotation\Inject;
use App\Model\AdminGroup;
use App\Kernel\Category;

class GroupService extends Service
{
    /**
     * @Inject
     * @var AdminGroup
     */
    protected $model;

    /**
     * [getDataList 获取列表]
     * @return    [array]                         
     */
    public function getDataList()
    {
        $cat = new Category($this->model, ['id', 'pid', 'title', 'title']);
        $data = $cat->getList([], 0, 'id');
        return $data;
    }
}
