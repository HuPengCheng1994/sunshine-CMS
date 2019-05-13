<?php
namespace app\admin\model;

use think\Model;

class User extends Model
{
    protected $pk = 'id';
    // 设置完整的数据表（包含前缀）
    protected $table = 'think_user';

    // 开启时间字段自动写入
    protected $autoWriteTimestamp = true;

    /**
     * 获取用户所属的角色信息
     */
    public function roles()
    {
        return $this->belongsToMany('Role', 'user_role');
    }

    /**
     * 注册一个新用户
     * @param  array $data 用户注册信息
     * @return integer|bool  注册成功返回主键，注册失败-返回false
     */
    public function register($data = [])
    {
        $data['type']       = 1;
        $data['status']     = 1;
        $data['nickname']   = '一个刚注册的小朋友';
        $data['password'] = md5($data['password']);
        $result           = $this->save($data);
        $role = 2;

        //开启事务
        $this->startTrans();
        $this->roles()->startTrans();
        try {
            $this->save($data);
            $this->roles()->attach($role);
            // 提交事务
            $this->commit();
            $this->roles()->commit();
            return $this->getData('id');
        } catch (\Exception $e) {
            // 回滚事务
            $this->rollback();
            $this->roles()->rollback();
            return false;
        }
    }

}
