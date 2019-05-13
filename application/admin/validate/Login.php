<?php
namespace app\admin\validate;

use think\Validate;

class Login extends Validate
{
    protected $rule = [
        'account'  => 'require',
        'password' => 'require',
        'captcha'  => 'require|captcha',

    ];

    protected $message = [
        'account.require'  => '账号必须',
        'password.require' => '密码必须',
        'captcha.require'  => '验证码必须',
        'captcha.captcha'  => '验证码错误',
    ];

}
