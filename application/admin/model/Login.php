<?php

namespace app\admin\model;

use think\Model;
use app\admin\model\RoleAuth;
use app\admin\model\UserRole;

class Login extends Model
{
    protected $pk = 'id';
    // 设置完整的数据表（包含前缀）
    protected $table = 'think_log';

    public function login($data)
    {
        $result = $this->loginCheck($data);
        if (isset($result['mid'])) {
            switch ($result['errorcode']) {
                case 1:
                    $msg = '进行【登录操作】，登录状态【登录失败】，原因【用户名输入不正确】';
                    break;
                case 2:
                    $msg = '进行【登录操作】，登录状态【登录失败】，原因【用户密码不正确】';
                    break;
                case 3:
                    $msg = '进行【登录操作】，登录状态【登录失败】，原因【用户已被锁定】';
                    break;
                case 4:
                    $msg = '进行【登录操作】，登录状态【登录成功】';
                    break;
            }
            $log = [
                'mid'  => $result['mid'],
                'ip'   => request()->ip(),
                'create_time' => time(),
                'msg'  => $msg,
                'type' => $result['type'],
            ];

            //查询当前用户记录条数
            $log_rows = $this->where('mid', $result['mid'])->count();

            if ($log_rows > 30) {
                //查询最早的一条记录
                $log_min = $this->where('mid', $result['mid'])->min('time');
                //删除
                $this->where('mid', $result['mid'])->where('time', $log_min)->delete();

            }

            //写入登录日志
            $this->save($log);
        }
        return $result;
    }

    /**
     * 登录验证
     * @param  [array] $data [登录信息]
     * @return [code]       [返回登录状态]
     */
    public function loginCheck($data)
    {
        $validate = validate('Login');
        if (!$validate->check($data)) {
            return ['code' => 2, 'msg' => $validate->getError()];
        }

        $res = User::where('account', $data['account'])->find();

        // 验证账号
        if (!$res) {
            return ['code' => 0, 'msg' => '用户名输入不正确','type'=>3,'errorcode'=>1];
        }

        // 验证密码
        if (md5($data['password']) != $res['password']) {
            return ['code' => 0, 'msg' => '用户密码不正确', 'mid' => $res['id'],'type'=>3,'errorcode'=>2];
        }

        //验证是否被锁定
        if ($res['status'] == 0) {
            return ['code' => 0, 'msg' => '用户已被锁定', 'mid' => $res['id'],'type'=>3,'errorcode'=>3];
        }

        //查询当前用户的角色
        $role_id = UserRole::where('user_id', $res['id'])->value('role_id');

        //查询当前用户的规则集
        $auth = RoleAuth::where('role_id', 'in', $role_id)->select();

        //合并当前用户的规则集
        $auth_str = '';
        $auth_arr = [];
        foreach ($auth as $key => $value) {
            $key ? $auth_str .= $value['auth_id'] : $auth_str .= $value['auth_id'] . ',';
        }

        //去重转为数组
        $auth_arr = array_unique(explode(',', $auth_str));

        //登录成功存入session
        session('loginname', $res['account'], 'admin');
        session('loginid', $res['id'], 'admin');
        session('loginstatus', $res['status'], 'admin');
        session('loginauth', $auth_arr, 'admin');

        return ['code' => 1, 'msg' => '登录成功', 'mid' => $res['id'],'type'=>2,'errorcode'=>4];

    }

}
