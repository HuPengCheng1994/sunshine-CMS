<?php

namespace app\admin\controller;

use app\admin\model\Login as LoginModel;
use think\Controller;

class Login extends Controller
{

    protected $model = null;

    public function initialize()
    {
        parent::initialize(); //继承父类初始化方法
        $this->model = new LoginModel;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {

        if (Request()->isAjax() && Request()->isPost()) {
            $data   = input();
            $result = $this->model->login($data);
            $result['code'] == 1 ? $this->success($result['msg'], 'admin/index/index') : $this->error($result['msg']);
        }

        if (session('loginname', '', 'admin') && session('loginid', '', 'admin')) {
            $this->redirect('admin/index/index');
        }
        return $this->fetch();

    }

    /**
     * 登出操作
     */
    public function Logout()
    {
        if (Request()->isAjax() && Request()->isGet()) {
            session(null, 'admin');
            $this->success('退出成功');
        }
    }
}
