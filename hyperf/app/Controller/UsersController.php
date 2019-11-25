<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;
use App\Service\UserService;
use App\Request\ChangePwdRequest;
use App\Request\EnablesRequest;
use App\Request\DeletesRequest;
use App\Request\UserStoreRequest;
use App\Request\UserUpdateRequest;

class UsersController extends AbstractController
{
    /**
     * @Inject
     * @var UserService
     */
    protected $service;

    public function index()
    {
        $keywords = $this->request->input('keywords');
        $page = $this->request->input('page', 1);
        $limit = $this->request->input('limit', $this->limit);
        $data = $this->service->getDataList($keywords, (int) $page, (int) $limit);
        return success($data);
    }

    public function show($id)
    {
        $data = $this->service->getDataById((int) $id);
        return success($data);
    }

    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();
        $params = $this->request->all();
        $data = $this->service->createData($params);
        return success($data);
    }

    public function update($id, UserUpdateRequest $request)
    {
        $validated = $request->validated();
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

    public function changePwd(ChangePwdRequest $request)
    {
        $validated = $request->validated();
        $oldPwd = $this->request->input('old_pwd');
        $newPwd = $this->request->input('new_pwd');
        $data = $this->service->changePwd($oldPwd, $newPwd);
        return success($data);
    }
}
