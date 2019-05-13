<?php

namespace app\admin\model;

use app\admin\model\Cate as CateModel;
use think\Model;

class Article extends Model
{
    protected $pk = 'id';

    // 设置完整的数据表（包含前缀）
    protected $table = 'think_article';

    public function getNowCate($id)
    {
        $nowCate             = CateModel::where('id', $id)->find();
        $nowCate['pid_name'] = CateModel::where('id', $nowCate['pid'])->value('name');
        return $nowCate;
    }

}
