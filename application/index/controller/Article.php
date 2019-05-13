<?php

namespace app\index\controller;

use app\index\model\Article as ArticleModel;
use app\index\model\Cate as CateModel;
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
        $nowCate = $this->model->getNowCate($cid);
        $data = $this->model->where('cid', $nowCate['id'])->paginate(9);
        $this->assign(['nowCate' => $nowCate, 'data' => $data]);
        switch ($nowCate['type']) {
            case '1':
                $data['0'] ? $article = $data['0'] : $article = ArticleModel::create(['cid' => $cid, 'tiem' => time()]);
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
     * 显示指定的资源
     *
     * @param  int $id
     * @return \think\Response
     */
    public function content($id)
    {
        $article = $this->model->get($id);
        $nowCate = CateModel::where('id', $article['cid'])->find();
        return $this->fetch('content', ['article' => $article, 'nowCate' => $nowCate]);
    }

}
