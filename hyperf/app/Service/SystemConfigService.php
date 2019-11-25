<?php

declare(strict_types=1);

namespace App\Service;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Cache\Annotation\CacheEvict;
use Hyperf\DbConnection\Db;
use App\Model\SystemConfig;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;

class SystemConfigService extends Service
{
    /**
     * @Inject
     * @var SystemConfig
     */
    protected $model;

    /**
     * 
     * @Cacheable(prefix="systemConfig", ttl=9000, listener="systemConfigUpdate")
     */
    public function getDataList(): array
    {
        $data = [];
        $systemConfig = $this->model->get();
        if ($systemConfig) {
            foreach ($systemConfig as $config) {
                $data[$config['key']] = $config['value'];
            }
        }

        return $data;
    }

    /**
     * @CacheEvict(prefix="systemConfig", value="")
     */
    public function createData($params)
    {
        Db::beginTransaction();
        try {
            foreach ($params as $key => $value) {
                $this->model->where('key', $key)->update(['value' => $value]);
            }
            Db::commit();
            return true;
        } catch (\Throwable $th) {
            Db::rollback();
            throw new BusinessException(ErrorCode::UPDATE_ERROR, $th->getMessage());
        }
    }
}
