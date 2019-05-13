<?php
namespace app\admin\validate;

use think\Validate;

class Config extends Validate
{
    protected $rule = [
        'name'      => 'require',
        '__token__' => 'token',

    ];

    protected $message = [
        'name.require'    => '网站名称必须',
        '__token__.token' => '请勿重复提交',
    ];

}
