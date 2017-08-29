<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2017/8/25
 * Time: 下午6:40
 */

namespace app\admin\controller;

use think\controller;
use think\session;
use think\Db;

class Base extends controller
{
    public function _initialize()
    {
        $where = array(
            'username' => Session::get('username'),
            'key' => Session::get('key'),
        );
        $isLogin = Db::table('admin')->where($where)->find();
        if (!$isLogin){
            $this->success('请先登录!','/admin/Login/login');
        }
    }
}