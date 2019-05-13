<?php
namespace app\admin\validate;

use app\admin\model\User as UserModel;
use think\Validate;

class UserUpdate extends Validate
{
    public $id;
    protected $rule = [
        'nickname'  => 'require|chsAlphaNum',
        'role'      => 'require',
        'passwords' => 'checkPasswords:1',
        'password'  => 'max:15|min:5',
        'phone'     => 'require|max:11|min:11',
        'email'     => 'require|email',
        '__token__' => 'token',
    ];

    protected $message = [

        'nickname.require'     => '昵称必须',
        'nickname.chsAlphaNum' => '昵称只能是汉字、字母和数字',
        'password.max'         => '密码不能大于15位',
        'password.min'         => '密码不能小于5位',
        'role.require'         => '角色必须',
        'email.require'        => '邮箱必须',
        'email.email'          => '邮箱格式错误',
        'phone.require'        => '手机号必须',
        'phone.max'            => '手机号必须11位',
        'phone.min'            => '手机号必须11位',
        '__token__.token'      => '请勿重复提交',
    ];

    // 自定义验证规则
    protected function checkPasswords($value)
    {
        $password = UserModel::where('id', $this->id)->value('password');
        $value    = md5($value);
        if ($value != $password) {
            return '旧密码不正确';
        } else {
            return true;
        }
    }

    protected $scene = [
        'change'   => ['nickname', 'role', 'passwords', 'password', 'phone', 'email', '__token__'],
        'nochange' => ['nickname', 'role', 'phone', 'email', '__token__'],
    ];

}
