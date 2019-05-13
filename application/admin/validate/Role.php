<?php

namespace app\admin\validate;

use think\Validate;

class Role extends Validate
{

    protected $rule = [
        'name'      => 'require',
        '__token__' => 'token',

    ];

    protected $message = [
        'name.require'    => '角色名称必须',
        '__token__.token' => '请勿重复提交',
    ];
}
