<?php

declare(strict_types=1);

namespace App\Service;

use Hyperf\Di\Annotation\Inject;
use App\Model\AdminPost;

class PostService extends Service
{
    /**
     * @Inject
     * @var AdminPost
     */
    protected $model;

    /**
     * [getDataList 获取列表]
     * @return    [array]                         
     */
    public function getDataList($keywords)
    {
        $query = $this->model;
        if ($keywords) {
            $keywords = rtrim(ltrim($keywords));
            $query = $query->where('name', 'like', "%{$keywords}%");
        }
        $data = $query->get();
        return $data;
    }
}
