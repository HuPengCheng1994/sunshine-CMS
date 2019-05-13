<?php

namespace app\admin\controller;

use app\admin\model\User as UserModel;
use app\admin\validate\User as UserValidate;
use think\Controller;
use think\Request;

class Register extends Controller
{
    protected $model = null;

    public function initialize()
    {
        $this->model = new UserModel;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        return $this->fetch();
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
            $data = input();

            //实例化验证类
            $validate = new UserValidate;
            if (!$validate->scene('register')->check($data)) {
                $this->error($validate->getError());
            }

            $result = $this->model->register($data);
            $result ? $this->success('注册成功') : $this->error('注册失败');

        }
    }

}
