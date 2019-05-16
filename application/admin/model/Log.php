<?php

namespace app\admin\model;
use app\admin\model\Auth;
use think\Db;

use think\Model;

class Log extends Model
{

    protected $autoWriteTimestamp = true;   //时间戳自动写入

    //获取器
    protected function getCreateTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    public function user()
    {
        return $this->belongsTo('User','mid','id');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function saveLog($code,$status,$mca,$database,$id,$type)
    {
        $mca_name    = Auth::where('permission',$mca)->value('name');
        $code ? $msg = '进行【' . $mca_name . '】操作，操作状态【成功】' : $msg = '进行【' . $mca_name . '】操作，操作状态【失败】';
        switch ($database) {
            case 'Article':
                $value = 'title';
                break;
            case 'User':
                $value = 'nickname';
                break;
            default:
                $value = 'name';
                break;
        }
        switch ($status) {
            case 'save':
                $code ? $content = DB::name($database)->where('id',$id)->value($value) : $content = '';
                break;
            case 'update':
                $content = DB::name($database)->where('id',$id)->value($value);
                break;
            case 'delete':
                $content = $id;
                break;
        }
        $ip          = request()->ip();
        $mid         = session('loginid', '', 'admin');
        $data        = [
			'mid'     =>$mid,
			'ip'      =>$ip,
			'msg'     =>$msg,
			'type'    =>$type,
			'content' =>$content
        ];
        $this->save($data);
    }
}
