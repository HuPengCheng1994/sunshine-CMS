<?php

namespace app\admin\validate;

use think\Validate;

class Link extends Validate
{
    protected $rule = [
        'name|链接名称' => 'require',
        'url|链接地址'  => 'require',
        '__token__' => 'token',

    ];

    protected $message = [
        '__token__.token' => '请勿重复提交',
    ];
}
