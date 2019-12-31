<?php

namespace App\Kernel;

/**
 * 分类管理
 */
class Category
{
    private $model; //分类的数据表模型
    private $rawList = []; //原始的分类数据
    private $formatList = []; //格式化后的分类
    private $icon = ['  │', '  ├ ', '  └ ']; //格式化的字符
    private $fields = []; //字段映射, 分类id, 父级分类fid, 分类名称name, 格式化后分类名称fullname

    /**
     * 构造函数, 对象初始化
     * @param object $model 数组或对象, 基于TP5.0的数据表模型名称,若不采用TP, 可传递空值。
     * @param array $fields 字段映射, 分类cid, 父级分类fid, 分类名称name, 格式化后分类名称fullname
     */
    public function __construct($model = null, $fields = [])
    {
        $this->model = &$model;
        $this->fields['cid'] = $fields['0'] ?? 'cid';
        $this->fields['fid'] = $fields['1'] ?? 'fid';
        $this->fields['name'] = $fields['2'] ?? 'name';
        $this->fields['fullname'] = $fields['3'] ?? 'fullname';
    }

    /**
     * 获取分类信息数据
     * @param array|string $condition 查询条件
     * @param string $orderby 排序
     */
    private function _findAllCat($condition, $orderby = NULL)
    {
        $model = $this->model->where($condition);
        if ($orderby) $model = $model->orderBy($orderby);
        $model = $model->get();
        $data = $model->toArray();
        $this->rawList = $data;
    }

    /**
     * 返回给定父级分类$fid的所有同一级子分类
     * @param int $fid 传入要查询的fid
     * @return array 返回结构信息
     */
    public function getChild($fid)
    {
        $childs = [];
        foreach ($this->rawList as $Category) {
            if ($Category[$this->fields['fid']] == $fid)
                $childs[] = $Category;
        }
        return $childs;
    }

    /**
     * 递归格式化分类前的字符
     * @param int $cid 分类cid
     * @param string $space
     */
    private function _searchList($cid = 0, $space = "", $level = 1, $p_name = '')
    {
        //下级分类的数组
        $childs = $this->getChild($cid);
        //如果没下级分类, 结束递归
        $n = count($childs);
        if (!$n) return;
        //循环所有的下级分类
        for ($i = 0; $i < $n; $i++) {
            $pre = "";
            $pad = "";
            $pre = $this->icon[1];
            $pad = $space ? $this->icon[0] : "";
            if ($n == $i + 1) {
                $pre = $this->icon[2];
            } else {
            }
            $childs[$i]['p_title'] = $p_name;
            $childs[$i]['else'] = $childs[$i][$this->fields['name']];
            $childs[$i][$this->fields['fullname']] = ($space ? $space . $pre : "") . $childs[$i][$this->fields['name']];
            $childs[$i]['level'] = $level;
            $this->formatList[] = $childs[$i];
            $this->_searchList($childs[$i][$this->fields['cid']], $space . $pad . " ", $level + 1, $childs[$i]['else']); //递归下一级分类
        }
    }

    /**
     * 不采用数据模型时, 可以从外部传递数据, 得到递归格式化分类
     * @param array|string $condition 条件
     * @param int $cid 起始分类
     * @param string $orderby 排序
     * @return  array 返回结构信息
     */
    public function getList($condition = NULL, $cid = 0, $orderby = NULL)
    {
        unset($this->rawList, $this->formatList);
        $this->_findAllCat($condition, $orderby);
        $this->_searchList($cid);
        return isset($this->formatList) ? $this->formatList : [];
    }

    /**
     * 获取结构
     * @param array $data 二维数组数据
     * @param int $cid 起始分类
     * @return array 递归格式化分类数组
     */
    public function getTree($data, $cid = 0)
    {
        unset($this->rawList, $this->formatList);
        $this->rawList = $data;
        $this->_searchList($cid);
        return $this->formatList;
    }

    /**
     * 检查分类参数$cid,是否为空
     * @param int $cid 起始分类
     * @return boolean 递归格式化分类数组
     */
    private function _checkCatID($cid)
    {
        if (intval($cid)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 检查分类参数$cid,是否为空
     * @param int $cid 分类cid
     */
    private function _searchPath($cid)
    {
        //检查参数
        if (!$this->_checkCatID($cid)) return false;
        $rs = $this->model->find($cid); //初始化对象, 查找父级Id；
        $this->formatList[] = $rs; //保存结果
        $this->_searchPath($rs[$this->fields['fid']]);
    }

    /**
     * 查询给定分类cid的路径
     * @param int $cid 分类cid
     * @return array 数组
     */
    public function getPath($cid)
    {
        unset($this->rawList, $this->formatList);
        $this->_searchPath($cid); //查询分类路径
        return array_reverse($this->formatList);
    }

    /**
     * 添加分类
     * @param array $data 一维数组, 要添加的数据, $data需要包含父级分类ID。
     * @return boolean 添加成功, 返回相应的分类ID,添加失败, 返回FALSE；
     */
    public function add($data)
    {
        if (empty($data)) return false;
        return $this->model->data($data)->add();
    }

    /**
     * 修改分类
     * @param array $data 一维数组, $data需要包含要修改的分类cid。
     * @return boolean 组修改成功, 返回相应的分类ID,修改失败, 返回FALSE；
     */
    public function edit($data)
    {
        if (empty($data)) return false;
        return $this->model->data($data)->save();
    }

    /**
     * 删除分类
     * @param int $cid 分类cid
     * @return boolean 删除成功, 返回相应的分类ID,删除失败, 返回FALSE
     */
    public function del($cid)
    {
        $cid = intval($cid);
        if (empty($cid)) return false;
        $conditon[$this->fields['cid']] = $cid;
        return $this->model->where($conditon)->delete();
    }
}
