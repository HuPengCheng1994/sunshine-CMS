<?php

namespace app\admin\controller;

use app\admin\model\Role as RoleModel;
use app\admin\model\User as UserModel;
use app\admin\model\UserRole as UrModel;
use think\Request;

class User extends Common
{

    protected $model = null;

    public function initialize()
    {
        parent::initialize(); //继承父类初始化方法
        $this->model = new UserModel;
    }

    /**
     * 显示用户列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $keyword = input('keyword');
        if (empty($keyword)) {
            $data = $this->model->where('type', 1)->paginate(10);
        } else {
            $data = $this->model->where('account|nickname|phone|email', 'like', '%' . $keyword . '%')->where('type', 1)->paginate(10, false, ['query' => request()->param()]);
        }
        return view('index', ['data' => $data, 'keyword' => $keyword]);
    }

    /**
     * 显示创建用户单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $data = RoleModel::all();
        return view('create', ['data' => $data]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        if (Request()->isAjax() && Request()->isPost()) {
            $data = input();
            //实例化验证类
            $validate = validate('User');
            if (!$validate->scene('create')->check($data)) {
                return ['code' => 2, 'msg' => $validate->getError()];
            }
            $role = implode(',', $data['role']);
            $data['type'] = 1;
            $data['password'] = md5($data['password']);
            //开启事务
            $this->model->startTrans();
            $this->model->roles()->startTrans();
            try {
                $this->model->save($data);
                $this->model->roles()->attach($role);
                // 提交事务
                $this->model->commit();
                $this->model->roles()->commit();
                $result = true;
            } catch (\Exception $e) {
                // 回滚事务
                $this->model->rollback();
                $this->model->roles()->rollback();
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
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {

        $role = RoleModel::all();
        $user = UserModel::get($id);
        $roles = $user->roles;
        foreach ($roles as $value) {
            $user['role'] = $value->pivot;
        }
        return view('edit', ['data' => $user, 'role' => $role]);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request $request
     * @param  int $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        if (Request()->isAjax() && Request()->isPost()) {
            $data = input();
            //超级管理员不能禁用
            if ($id == 1 && $data['status'] == 0) {
                $this->error('admin用户不能禁用');
            }
            $validate = validate('UserUpdate');
            $validate->id = $id;
            //修改密码需要验证密码规则
            if (!empty(trim($data['passwords']))) {
                if (!$validate->scene('change')->check($data)) {
                    $this->error($validate->getError());
                }
                $data['password'] = md5($data['password']);
            } else {
                if (!$validate->scene('nochange')->check($data)) {
                    $this->error($validate->getError());
                }
                unset($data['passwords'], $data['password']);
            }
            $role = implode(',', $data['role']);
            $user = UserModel::get($id);
            $ur = new UrModel;
            //开启事务
            $user->startTrans();
            $ur->startTrans();
            try {
                $user->save($data);
                $ur->save(['role_id' => $role], ['user_id' => $id]);
                // 提交事务
                $user->commit();
                $ur->commit();
                $result = true;
            } catch (\Exception $e) {
                // 回滚事务
                $user->rollback();
                $ur->rollback();
                $result = false;
            }

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
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if (Request()->isAjax() && Request()->isGet()) {
            //超级管理员不能禁用
            if ($id == 1) {
                $this->error('admin用户不能删除');
            }
            // 开启事务
            $user = UserModel::get($id);
            $user->startTrans();
            try {
                $user->delete();
                $user->roles()->detach();
                // 提交事务
                $user->commit();
                $user->roles()->commit();
                $result = true;
            } catch (\Exception $e) {
                // 回滚事务
                $user->rollback();
                $user->roles()->rollback();
                $result = false;
            }
            if($result) {
                $this->log->saveLog(true,$this->a,$this->mca,$this->c,$user['nickname'],3);
                $this->success('删除成功');
            } else {
                $this->log->saveLog(false,$this->a,$this->mca,$this->c,$user['nickname'],3);
                $this->error('删除失败');
            }
        }
    }
}
