<?php

namespace app\admin\validate;

use think\Validate;

class Cate extends Validate
{
    protected $rule = [
        'name'       => 'require',
        '__token__'  => 'token',

    ];

    protected $message = [
        'name.require'       => '规则名称必须',
        '__token__.token'    => '请勿重复提交',
    ];
}
