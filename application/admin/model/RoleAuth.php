<?php
namespace app\admin\model;

use think\Model;

class RoleAuth extends Model
{
    protected $pk = 'id';
    // 设置完整的数据表（包含前缀）
    protected $table = 'think_role_auth';

}