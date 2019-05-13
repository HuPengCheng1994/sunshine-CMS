<?php

namespace app\admin\controller;

use app\admin\model\Config as ConfigModel;
use think\Controller;
use think\Request;

class Config extends Common
{

    protected $model = null;

    public function initialize()
    {
        parent::initialize(); //继承父类初始化方法
        $this->model = new ConfigModel;
    }

    /**
     * 网站配置
     *
     * @return \think\Response
     */
    public function index()
    {

        if (Request()->isAjax() && Request()->isPost()) {
            $data     = input();
            $validate = validate('Config');
            if (!$validate->check($data)) {
                $this->error($validate->getError());
            }
            $config           = $this->model->get(1);
            $config ? $result = $this->model->save($data, ['id' => 1]) : $result = $this->model->save($data);
            $result ? $this->success('编辑成功') : $this->error('编辑失败');

        } else {
            $data = ConfigModel::get(1);
            return view('index', ['data' => $data]);
        }
    }

}
