<?php
namespace app\admin\model;

use think\Model;

class Role extends Model
{
    protected $pk = 'id';
    
    // 设置完整的数据表（包含前缀）
    protected $table = 'think_role';

    //自动写入时间戳
    protected $autoWriteTimestamp = true;

    /**
     * 获取角色所属的权限信息
     */
    public function auths()
    {
        return $this->belongsToMany('Auth', 'role_auth');
    }
}