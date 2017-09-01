<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2017/8/31
 * Time: 上午12:49
 */

namespace app\admin\controller;

use think\Session;
use think\View;
use think\Db;

class Admin extends Base
{

    public function adminUser()
    {
        $view = new View();
        $adminUser = Db::table('admin')->find();
        $view->assign('user', $adminUser);
        return $view->fetch();
    }

    public function edit()
    {
        $post = input('post.');

        $update = array(
            'username' => $post['username'],
            'email' => $post['email'],
            'phone' => $post['phone'],
            'weibo' => $post['weibo'],
            'intro' => $post['intro']
        );

        $editRes = Db::table('admin')->where('id = 1')->update($update);

        if ($editRes !== false) {
            $this->success('修改成功!', '/admin/Index/index');
        } else {
            $this->error('修改失败，请重试！');
        }

    }

    public function password()
    {
        $view = new View();

        return $view->fetch();
    }

    public function passedit()
    {
        $post = input('post.');

        $password = $post['password'];

        $is_pass = Db::table('admin')->where("password = $password")->find();
        if ($is_pass) {

            $newPassword = $post['new password'];
            $passRes = Db::table('admin')->where("password = $password")->update("password = $newPassword");
            if ($passRes !== false) {
                Session::set('username', null);
                Session::set('key', null);
                $this->success('修改成功,请重新登录!','/admin/Login/login');
            }
            $this->error('修改失败!!!');
        }
        $this->error('错误!!!');
    }
}