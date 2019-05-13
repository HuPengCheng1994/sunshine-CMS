<?php

namespace app\index\model;
use app\index\model\Cate as CateModel;

use think\Model;

class Article extends Model
{
    public function cate()
    {
        return $this->belongsTo('Cate','cid');
    }
    public function getNowCate($id)
    {
        $nowCate             = CateModel::where('id', $id)->find();
        $nowCate['pid_name'] = CateModel::where('id', $nowCate['pid'])->value('name');
        //判断是不是一级栏目如何是1级栏目就返回一级栏目下的第一个子栏目
        if($nowCate['pid'] == 0) {
        	$cate = CateModel::where('pid', $nowCate['id'])->value('id');
        	$data = CateModel::where('id', $cate)->find();
        	$data['pid_name'] = CateModel::where('id', $nowCate['id'])->value('name');
        	return $data;
        }
        return $nowCate;
    }
}
