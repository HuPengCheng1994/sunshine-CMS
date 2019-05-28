<?php

namespace app\admin\controller;
use app\admin\model\Log as LogModel;

class Log extends Common
{

    protected $model = null;

    public function initialize()
    {
        parent::initialize(); //继承父类初始化方法
        $this->model = new LogModel;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index($type=0)
    {
        switch ($type) {
            case '0':
                $data = $this->model->relation('user')->order('create_time','desc')->paginate(10);
                break;
            case '1':
                $data = $this->model->relation('user')->where('type',1)->order('create_time','desc')->paginate(10);
                break;
            case '2':
                $data = $this->model->relation('user')->where('type',2)->order('create_time','desc')->paginate(10);
                break;
            case '3':
                $data = $this->model->relation('user')->where('type',3)->order('create_time','desc')->paginate(10);
                break;
        }

        
        $currentPage = $data->currentPage();       //获取当前页
        $listRows = $data->listRows();  //获取分页数
        return view('index', ['data' => $data,'currentPage'=>$currentPage,'listRows'=>$listRows,'type'=>$type]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
