<?php

namespace app\admin\model;

use think\Model;

class Cate extends Model
{
    protected $pk = 'id';

    // 设置完整的数据表（包含前缀）
    protected $table = 'think_cate';

    /**
     * 获取规则列表-优化-引用方式
     * @param  [arr]  $data  [包含所有栏目分类的数组]
     * @param  integer $pid   [上级规则标识]
     * @param  integer $level [规则层级]
     * @return [arr]
     */
    public function getCate($data, &$arr = [], $pid = 0, $level = -1)
    {
        //初始层级为0每次递归查询+1
        $level += 1;
        //层级为0则不需要加|
        $level ? $str = '|' : $str = '';

        foreach ($data as $key => $value) {
            //取出当前层级下的所有子集
            if ($pid == $value['pid']) {
                $tmp_arr           = array();
                $tmp_arr['id']     = $value['id'];
                $tmp_arr['name']   = $str . str_repeat('--', $level) . $value['name'];
                $tmp_arr['pid']    = $value['pid'];
                $tmp_arr['type']   = $value['type'];
                $tmp_arr['status'] = $value['status'];

                $arr[] = $tmp_arr;
                self::getCate($data, $arr, $value['id'], $level);
                unset($value);
            }
        }

        //因为$arr是引用变量每次都指向同一内存的数组所以不需要return

    }
}
