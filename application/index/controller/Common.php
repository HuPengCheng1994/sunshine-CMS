<?php

namespace app\index\controller;

use app\index\model\Cate as CateModel;
use app\index\model\Config as ConfigModel;
use app\index\model\User;
use app\index\model\Link;
use think\Controller;

class Common extends Controller
{
    protected function initialize()
    {

        parent::initialize(); //继承父类初始化方法

        //查询所有栏目
        $cate   = CateModel::all();
        //查询配置信息
        $config = ConfigModel::get(1);
        //查询友情链接
        $link   = link::all();

        $this->assign(['cate' => $cate, 'config' => $config,'link'=>$link]);

    }
}
