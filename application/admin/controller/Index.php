<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;

class Index extends Common
{

    protected $model = null;

    public function initialize()
    {
        parent::initialize(); //继承父类初始化方法

    }

    public function index()
    {
    	$info['date'] = date('Y-m-d',time());
        $info['time'] = date('H:i:s',time());
        $info['number'] = '1.0';
        $info['version'] = '内测版';

        $free= round(@disk_free_space(".")/(1024*1024*1024),3); //可用
        $total= round(@disk_total_space(".")/(1024*1024*1024),3); //总
        $used=$total-$free;
        $usp= (floatval($total)!=0)?round($used/$total*100,2):0;


        //获取系统信息
        $system = [
            'ip' => $_SERVER['REMOTE_ADDR'],    //获取ip地址
            'host' => $_SERVER['SERVER_NAME'],  //获取主机名
            'os' => php_uname(),    //系统版本
            'server' => $_SERVER['SERVER_SOFTWARE'],    //运行环境
            'port' => $_SERVER['SERVER_PORT'],      //服务器端口
            'php_ver' => PHP_VERSION,   //php版本
            'mysql_ver' => Db::query('select version() as ver')['0']['ver'],    //获取mysql版本
            'database' => config('database')['database'],       //获取数据库名
            'total' => $total,
            'free' => $free,
        ];

        
        $this->assign([
            'info' => $info,
            'system' => $system,

        ]);
        return $this->fetch();
    }
}
