<?php

namespace app\index\model;

use think\Model;

class Link extends Model
{
    protected $pk = 'id';
    // 设置完整的数据表（包含前缀）
    protected $table = 'think_link';
}
