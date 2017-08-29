<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2017/8/24
 * Time: 下午6:26
 */
namespace app\admin\controller;



use think\Db;
use think\View;
use think\controller;
use think\session;


class Login extends controller{

    public function login(){

        //利用session判断是否登录，登录的话重定向到后台首页
        $where = array(
            'username' => Session::get('username'),
            'key' => Session::get('key'),
        );
        $isLogin = Db::table('admin')->where($where)->find();
        if ($isLogin){
            $this->success('已登录!','/admin/Index/index');
        }

        $view = new View();

        $post = input('post.');

        //登录操作，并在session里面记录username和加密的key
        if (!empty($_POST)){

            $validate = $this->validate($post, 'User');

            if (true !== $validate){
                return $view->fetch('login', ['error' => $validate]);
            }

            $adminRes = Db::table('admin')->where($post)->select();

            if (!empty($adminRes)){
                Session::set('username', $post['username']);
                Session::set('key', sha1($post['username']));

                $this->success('登录成功','/admin/Index/index');
            }else{
                $this->fetch('login', ['error' => '用户名或密码错误！']);
            }

        }

        //直接点登录返回到登录界面
        return $view->fetch('login');
    }
}