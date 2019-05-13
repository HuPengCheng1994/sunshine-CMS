<?php

namespace app\admin\validate;

use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'title'       => 'require',
        'content'     => 'require',
        'description' => 'require',
        'keyword'     => 'require',
        'source'      => 'require',
        'author'      => 'require',
        '__token__'   => 'token',

    ];

    protected $message = [
        'title.require'       => '标题必须',
        'content.require'     => '内容必须',
        'description.require' => '描述必须',
        'keyword.require'     => '关键字必须',
        'source.require'      => '来源必须',
        'author.require'      => '作者必须',
        '__token__.token'     => '请勿重复提交',
    ];
}
