<?php
namespace app\admin\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'account'   => 'require|unique:user|alphaDash|max:15|min:5',
        'nickname'  => 'require|chsAlphaNum',
        'role'      => 'require',
        'passwords' => 'require',
        'password'  => 'require|confirm:passwords|max:15|min:5',
        'phone'     => 'require|max:11|min:11',
        'email'     => 'require|email',
        'captcha'   => 'require|captcha',
        '__token__' => 'token',

    ];

    protected $message = [
        'account.require'      => '账号必须',
        'account.unique'       => '账号已存在',
        'account.alphaDash'    => '账号只能是字母、数字和下划线_及破折号-',
        'account.max'          => '账号不能大于15位',
        'account.min'          => '账号不能小于5位',
        'nickname.require'     => '昵称必须',
        'nickname.chsAlphaNum' => '昵称只能是汉字、字母和数字',
        'passwords.require'    => '密码必须',
        'password.require'     => '确认密码必须',
        'password.confirm'     => '两次密码输入必须一致',
        'password.max'         => '密码不能大于15位',
        'password.min'         => '密码不能小于5位',
        'role.require'         => '角色必须',
        'email.require'        => '邮箱必须',
        'email.email'          => '邮箱格式错误',
        'phone.require'        => '手机号必须',
        'phone.max'            => '手机号必须11位',
        'phone.min'            => '手机号必须11位',
        'captcha.require'      => '验证码必须',
        'captcha.captcha'      => '验证码错误',
        '__token__.token'      => '请勿重复提交',

    ];

    protected $scene = [
        'register' => ['account', 'passwords', 'password', 'captcha'],
        'create'   => ['account', 'nickname', 'role', 'passwords', 'password', 'phone', 'email', '__token__'],
    ];

}
