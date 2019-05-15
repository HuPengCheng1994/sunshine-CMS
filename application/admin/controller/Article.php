<?php

namespace app\admin\controller;

use app\admin\model\Article as ArticleModel;
use think\Request;

class Article extends Common
{
    protected $model = null;

    public function initialize()
    {
        parent::initialize(); //继承父类初始化方法
        $this->model = new ArticleModel;
    }

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index($cid)
    {
        $data = $this->model->where('cid', $cid)->select();
        $nowCate = $this->model->getNowCate($cid);
        $this->assign(['nowCate' => $nowCate, 'data' => $data]);
        switch ($nowCate['type']) {
            case '1':
                $data['0'] ? $article = $data['0'] : $article = ArticleModel::create(['cid' => $cid, 'time' => time()]);
                return view('page', ['article' => $article]);
                break;
            case '2':
                return view('list');
                break;
            case '3':
                return view('image');
                break;
        }
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create($cid)
    {
        $nowCate = $this->model->getNowCate($cid);
        return view('create', ['nowCate' => $nowCate]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request, $cid)
    {
        if (Request()->isAjax() && Request()->isPost()) {
            $data = input();
            $data['cid'] = $cid;
            $data['time'] = time();
            $validate = validate('Article');
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
        $cid     = $this->model->where('id', $id)->value('cid');
        $nowCate = $this->model->getNowCate($cid);
        $article = $this->model->where('id', $id)->find();
        return view('edit', ['article' => $article, 'nowCate' => $nowCate]);
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
            $data['time'] = time();
            $validate = validate('Article');
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
     * @param  int $id
     * @return \think\Response
     */
    public function delete($id)
    {
        if (Request()->isAjax() && Request()->isGet()) {
            $article = $this->model->get($id);
            $result  = $article->delete();
            if($result) {
                $this->log->saveLog(true,$this->a,$this->mca,$this->c,$article['title'],3);
                $this->success('删除成功');
            } else {
                $this->log->saveLog(false,$this->a,$this->mca,$this->c,$article['title'],3);
                $this->error('删除失败');
            }
        }
    }
}
