<?php

declare(strict_types=1);

namespace App\Service;

use Hyperf\Di\Annotation\Inject;
use App\Model\AdminMenu;
use App\Kernel\Category;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Kernel\Tree;

class MenuService extends Service
{
    /**
     * @Inject
     * @var AdminMenu
     */
    protected $model;

    /**
     * @Inject
     * @var Tree
     */
    protected $tree;

    /**
     * [getDataList 获取列表]
     * @return    [array]                         
     */
    public function getDataList()
    {
        $cat = new Category($this->model, ['id', 'pid', 'title', 'title']);
        $data = $cat->getList([], 0, 'sort');
        return $data;
    }

    public function getDataById($id)
    {
        $result = $this->model
            ->leftJoin('admin_rule as rule', 'admin_menu.rule_id', '=', 'rule.id')
            ->select('admin_menu.*', 'rule.title as rule_name')
            ->find($id);
        if (!$result) {
            throw new BusinessException(ErrorCode::RECORD_NOT_EXIST);
        }
        return $result;
    }
}
