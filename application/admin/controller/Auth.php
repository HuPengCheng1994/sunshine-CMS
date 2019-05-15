<?php

namespace app\admin\controller;

use app\admin\model\Auth as AuthModel;
use think\Request;

class Auth extends Common
{
    protected $model = null;

    public function initialize()
    {
        parent::initialize(); //继承父类初始化方法
        $this->model = new AuthModel;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {

        $data = $this->model->all();
        $this->model->getAuth($data, $arr);
        return view('index', ['data' => $arr]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        $data = AuthModel::all();
        $this->model->getAuth($data, $arr);
        $this->assign('data', $arr);
        return view('create', ['data' => $arr]);
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
            $validate = validate('Auth');
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $result = $this->model->save($data);
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
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        $data = $this->model->all();
        $this->model->getAuth($data, $arr);
        $auth_data = $this->model->where('id', $id)->find();
        return view('edit', ['data' => $arr, 'auth' => $auth_data]);
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
            $validate = validate('Auth');
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $result = $this->model->save($data,['id'=>$id]);
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
            if($id == 1){
                return $this->error('删除失败，初始页面规则请勿删除');
            }
            $data = $this->model->where('pid', $id)->count();
            if ($data) {
                $this->error('请先删除子规则再尝试此规则');
            }
            $auth   = $this->model->get($id);
            $result = $auth->delete();
            if($result) {
                $this->log->saveLog(true,$this->a,$this->mca,$this->c,$auth['name'],3);
                $this->success('删除成功');
            } else {
                $this->log->saveLog(false,$this->a,$this->mca,$this->c,$auth['name'],3);
                $this->error('删除失败');
            }
        }
    }
}
