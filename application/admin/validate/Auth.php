<?php

namespace app\admin\validate;

use think\Validate;

class Auth extends Validate
{
    protected $rule = [
        'name'       => 'require',
        'permission' => 'require',
        '__token__'  => 'token',

    ];

    protected $message = [
        'name.require'       => '规则名称必须',
        'permission.require' => '规则必须',
        '__token__.token'    => '请勿重复提交',
    ];
}
