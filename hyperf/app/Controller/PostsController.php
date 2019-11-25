<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use App\Service\PostService;
use App\Request\EnablesRequest;
use App\Request\DeletesRequest;

class PostsController extends AbstractController
{
    /**
     * @Inject
     * @var PostService
     */
    protected $service;

    public function index()
    {
        $keywords = $this->request->input('keywords');
        $data = $this->service->getDataList($keywords);
        return success($data);
    }

    public function show($id)
    {
        $data = $this->service->getDataById((int) $id);
        return success($data);
    }

    public function store()
    {
        // $validated = $request->validated();
        $params = $this->request->all();
        $data = $this->service->createData($params);
        return success($data);
    }

    public function update($id)
    {
        // $validated = $request->validated();
        $params = $this->request->all();
        $data = $this->service->updateDataById($params, (int) $id);
        return success($data);
    }

    public function destroy($id)
    {
        $data = $this->service->delDataById((int) $id);
        return success($data);
    }

    public function deletes(DeletesRequest $request)
    {
        $validated = $request->validated();
        $ids = $this->request->input('ids');
        $data = $this->service->delDatas($ids);
        return success($data);
    }

    public function enables(EnablesRequest $request)
    {
        $validated = $request->validated();
        $ids = $this->request->input('ids');
        $status = $this->request->input('status');
        $data = $this->service->enableDatas($ids, $status);
        return success($data);
    }
}
