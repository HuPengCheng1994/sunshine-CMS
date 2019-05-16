<?php

namespace app\admin\controller;

use app\admin\model\Auth as AuthModel;
use app\admin\model\Role as RoleModel;
use app\admin\model\RoleAuth as RaModel;
use think\Request;

class Role extends Common
{
    protected $model = null;

    public function initialize()
    {
        parent::initialize(); //继承父类初始化方法
        $this->model = new RoleModel;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {

        $keyword = input('keyword');
        if (empty($keyword)) {
            $data = $this->model->order('create_time','desc')->paginate(10);
        } else {
            $data = $this->model->where('name', 'like', '%' . $keyword . '%')->order('create_time','desc')->paginate(10, false, ['query' => request()->param()]);
        }
        $currentPage = $data->currentPage();       //获取当前页
        $listRows = $data->listRows();  //获取分页数
        return view('index', ['data' => $data, 'keyword' => $keyword, 'currentPage' => $currentPage,'listRows'=>$listRows]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        return $this->fetch();
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        if (Request()->isAjax() && Request()->isPost()) {
            $data     = input();
            $validate = validate('Role');
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            //开启事务
            $this->model->startTrans();
            $this->model->auths()->startTrans();
            try {
                $this->model->save($data);
                $this->model->auths()->attach(1);
                // 提交事务
                $this->model->commit();
                $this->model->auths()->commit();
                $result = true;
            } catch (\Exception $e) {
                // 回滚事务
                $this->model->rollback();
                $this->model->auths()->rollback();
                $result = false;
            }
            if($result){
                $this->log->saveLog(true,$this->a,$this->mca,$this->c,$this->model->id,1);
                $this->success('添加成功');
            } else {
                $this->log->saveLog(false,$this->a,$this->mca,$this->c,0,3);
                $this->error('添加失败');
            }
        }
    }


    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = RoleModel::get($id);
        return view('edit', ['data' => $data]);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        if (Request()->isAjax() && Request()->isPost()) {
            $data     = input();
            //超级管理员不能禁用
            if ($id == 1 && $data['status'] == 0) {
                $this->error('超级管理员不能禁用');
            }
            $validate = validate('Role');
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $result = $this->model->save($data, ['id' => $id]);
            if($result){
                $this->log->saveLog(true,$this->a,$this->mca,$this->c,$id,1);
                $this->success('编辑成功');
            } else {
                $this->log->saveLog(false,$this->a,$this->mca,$this->c,$id,1);
                $this->error('编辑失败');
            }
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if (Request()->isAjax() && Request()->isGet()) {
            //超级管理员不能删除
            if ($id == 1) {
                $this->error('超级管理员角色不能删除');
            }
            // 开启事务
            $role = RoleModel::get($id);
            $role->startTrans();
            try {
                $role->delete();
                $role->auths()->detach();
                // 提交事务
                $role->commit();
                $role->auths()->commit();
                $result = true;
            } catch (\Exception $e) {
                // 回滚事务
                $role->rollback();
                $role->auths()->rollback();
                $result = false;
            }
            if($result) {
                $this->log->saveLog(true,$this->a,$this->mca,$this->c,$role['name'],3);
                $this->success('删除成功');
            } else {
                $this->log->saveLog(false,$this->a,$this->mca,$this->c,$role['name'],3);
                $this->error('删除失败');
            }

        }
    }

    /**
     * 权限配置
     * @param  [int] $id
     * @return \think\Response
     */
    public function config($id)
    {
        //查询所有规则
        $data = AuthModel::all();
        $auth = new AuthModel;

        //无限级规则处理
        $auth->getAuth($data, $arr);

        //获取当前角色信息
        $role = RoleModel::get($id);

        //获取当前角色权限
        $auths = $role->auths;
        foreach ($auths as $value) {
            $role['auth'] = $value->pivot;
        }
        
        return view('config', ['auth' => $arr, 'rid' => $id, 'role' => $role]);
    }

    /**
     * 权限配置保存
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function configSave($id)
    {
        if (Request()->isAjax() && Request()->isPost()) {
            $data                           = input();
            isset($data['auth_id']) ? $auth = implode(',', $data['auth_id']) : $auth = '';
            $ra                             = new RaModel;
            $result                         = $ra->save(['auth_id' => $auth], ['role_id' => $id]);
            $result ? $this->success('编辑成功') : $this->error('编辑失败');

        }
    }
}
