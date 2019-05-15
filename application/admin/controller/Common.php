<?php
namespace app\admin\controller;

use app\admin\model\Auth;
use app\admin\model\Cate as CateModel;
use app\admin\model\Log as LogModel;
use app\admin\model\User;
use think\Controller;

class Common extends Controller
{

    protected $mca = null; //当前操作
    protected $log = null; //日志模型
    protected $m   = null;
    protected $c   = null; 
    protected $a   = null;

    protected function initialize()
    {

        parent::initialize(); //继承父类初始化方法

        $m              = request()->module();
        $c              = request()->controller();
        $a              = request()->action();
        $this->mca      = $m . '/' . $c . '/' . $a;
        $this->log      = new LogModel;
        $this->m = $m;
        $this->c = $c;
        $this->a = $a;

        //登录状态验证
        if (!session('loginname', '', 'admin') || !session('loginid', '', 'admin')) {
            if ($controller === 'Index' && $action === 'index') {
                $this->redirect('admin/login/index');
            }
            $this->error('登录验证超时，请重新登录！', 'admin/login/index');
        }

        //查询当前登录用户信息
        $user = User::get(session('loginid', '', 'admin'));
        $this->assign('user', $user);
        
        //验证当前用户的权限
        $this->authCheck(session('loginauth', '', 'admin'));

        //查询所有栏目
        $cate = CateModel::all();
        $this->assign(['cate' => $cate]);

    }

    /**
     * 权限控制验证
     * @param  [int] $user_id    [当前用户id]
     * @param  [str] $module     [当前模型]
     * @param  [str] $controller [当前控制器]
     * @param  [str] $action     [当前方法]
     * @return [code]            [验证结果]
     */
    private function authCheck($auth)
    {
        //构造当前操作的规则
        $now = $this->mca;

        //查询当前规则id
        $auth_id = Auth::where('permission', $now)->value('id');

        //判断当前规则id在不在当前用户的规则集中
        if (!in_array($auth_id, $auth)) {
            $this->error('对不起，您无此栏目权限', 'admin/index/index');
        }
    }


}
