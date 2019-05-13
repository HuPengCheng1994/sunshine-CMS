<?php
namespace app\index\controller;

use think\Controller;
use app\index\model\Article as ArticleModel;

class Index extends Common
{
    /**
     * 首页展示文章
     * @return [type] [description]
     */
    public function index()
    {

    	$data = ArticleModel::relation('cate')->where('cid','<>',2)->order('time desc')->limit('20')->paginate(5);
        return $this->fetch('index',['data'=>$data]);
    }

    /**
     * 搜索功能
     * @return [type] [description]
     */
    public function search()
    {
    	$keyword = input('get.keyword');
    	$data = ArticleModel::relation('cate')
    		->where('title','like','%'.$keyword.'%')
    		->order('time desc')
    		->paginate(5,false,['query' => request()->param()]);
    	$count = ArticleModel::relation('cate')
    		->where('title','like','%'.$keyword.'%')
    		->count();
    	return $this->fetch('search',['data'=>$data,'keyword'=>$keyword,'count'=>$count]);
    }
}
