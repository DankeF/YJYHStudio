<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2017/8/23
 * Time: 下午6:34
 */
namespace app\admin\controller;

use think\Session;
use think\View;
use app\admin\controller;

class Index extends Base{
    public function index(){

        $view = new View();
        return $view->fetch();
    }

    public function loginOut(){
        Session::set('usename', null);
        Session::set('key', null);
        $this->success('已退出，请重新登录!', '/admin/Login/login');
    }
}