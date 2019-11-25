<?php

declare(strict_types=1);

namespace App\Service;

use Hyperf\Di\Annotation\Inject;
use App\Model\AdminRule;
use App\Kernel\Category;
use App\Kernel\Tree;

class RuleService extends Service
{
    /**
     * @Inject
     * @var AdminRule
     */
    protected $model;

    /**
     * @Inject
     * @var Tree
     */
    protected $tree;

    /**
     * 获取列表
     *
     * @param string $type 类型：tree为树结构
     * @return array
     */
    public function getDataList($type = '')
    {
        $cat = new Category($this->model, ['id', 'pid', 'title', 'title']);
        $data = $cat->getList([], 0, 'id');
        if ($type == 'tree') {
            foreach ($data as $k => $v) {
                $data[$k]['check'] = false;
            }
            $data = $this->tree->list_to_tree($data, 'id', 'pid', 'child', 0, true, ['pid']);
        }
        return $data;
    }
}
