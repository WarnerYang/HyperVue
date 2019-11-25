<?php

declare(strict_types=1);

namespace App\Service;

use Psr\EventDispatcher\EventDispatcherInterface;
use Hyperf\Di\Annotation\Inject;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Event\UserPermissionChanged;

abstract class Service
{

    /**
     * @Inject
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    protected $model;

    /**
     * 根据主键获取详情
     *
     * @param integer $id 用户id
     * @return array
     */
    public function getDataById($id)
    {
        $result = $this->model->find($id);
        if (!$result) {
            throw new BusinessException(ErrorCode::RECORD_NOT_EXIST);
        }
        return $result;
    }

    /**
     * 新增数据
     *
     * @param array $params 参数数组
     * @return integer
     */
    public function createData($params)
    {
        try {
            $model = $this->model;
            $result = $model->create($params);
            return ['id' => $result->id];
        } catch (\Throwable $th) {
            throw new BusinessException(ErrorCode::INSERT_ERROR, $th->getMessage());
        }
    }

    /**
     * 根据id更新数据
     *
     * @param array $params 参数数组
     * @param integer $id 主键id
     * @return integer
     */
    public function updateDataById($params, $id)
    {
        try {
            $model = $this->model->find($id);
            $result = $model->fill($params)->save();
            $this->eventDispatcher->dispatch(new UserPermissionChanged($this->model, $id));
            return ['id' => $id];
        } catch (\Throwable $th) {
            throw new BusinessException(ErrorCode::UPDATE_ERROR, $th->getMessage());
        }
    }

    /**
     * 根据id删除数据
     *
     * @param string $id 主键
     * @param boolean $delSon 是否删除子孙数据
     * @return boolean
     */
    public function delDataById($id, $delSon = false)
    {
        try {
            // 查找所有子元素
            if ($delSon) {
                $ids = $this->getAllChild($id);
                array_push($ids, $id);
            } else {
                $ids = [$id];
            }
            $ids = array_unique($ids);
            $result = $this->model->whereIn('id', $ids)->delete();
            if (!$result) {
                throw new BusinessException(ErrorCode::DELETE_ERROR);
            }
            $this->eventDispatcher->dispatch(new UserPermissionChanged($this->model, $id));
            return ['id' => $id];
        } catch (\Throwable $th) {
            throw new BusinessException(ErrorCode::DELETE_ERROR, $th->getMessage());
        }
    }

    /**
     * 批量删除数据
     *
     * @param array $ids 主键数组
     * @param boolean $delSon 是否删除子孙数据
     * @return boolean
     */
    public function delDatas($ids = [], $delSon = false)
    {
        try {
            // 查找所有子元素
            if ($delSon) {
                foreach ($ids as $id) {
                    if (!is_numeric($id)) continue;
                    $childIds = $this->getAllChild($id);
                    $ids = array_merge($ids, $childIds);
                }
            }
            $ids = array_unique($ids);
            $result = $this->model->whereIn('id', $ids)->delete();
            if (!$result) {
                throw new BusinessException(ErrorCode::DELETE_ERROR);
            }
            $this->eventDispatcher->dispatch(new UserPermissionChanged($this->model, $ids));
            return ['ids' => $ids];
        } catch (\Throwable $th) {
            throw new BusinessException(ErrorCode::DELETE_ERROR, $th->getMessage());
        }
    }

    /**
     * 批量 启用/禁用
     *
     * @param array $ids 主键数组
     * @param integer $status 状态: 1启用 0禁用
     * @param boolean $delSon 是否删除子孙数组
     * @return boolean
     */
    public function enableDatas($ids = [], $status = 1, $delSon = false)
    {
        try {
            // 查找所有子元素
            if ($delSon && (int) $status === 0) {
                foreach ($ids as $id) {
                    $childIds = $this->getAllChild($id);
                    $ids = array_merge($ids, $childIds);
                }
            }
            $ids = array_unique($ids);
            $result = $this->model->whereIn('id', $ids)->update(['status' => $status]);
            if (!$result) {
                throw new BusinessException(ErrorCode::OPERATION_FAILED);
            }
            $this->eventDispatcher->dispatch(new UserPermissionChanged($this->model, $ids));
            return ['ids' => $ids];
        } catch (\Throwable $th) {
            throw new BusinessException(ErrorCode::OPERATION_FAILED, $th->getMessage());
        }
    }

    /**
     * 获取所有子孙
     *
     * @param integer $id 父级id
     * @param array $data 子元素id数组
     * @return array
     */
    public function getAllChild($id, &$data = [])
    {
        $childIds = $this->model->where('pid', $id)->pluck('id');
        if (!empty($childIds)) {
            foreach ($childIds as $id) {
                $data[] = $id;
                $this->getAllChild($id, $data);
            }
        }
        return $data;
    }
}
