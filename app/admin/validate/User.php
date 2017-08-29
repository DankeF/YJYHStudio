<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2017/8/24
 * Time: 下午5:42
 */
namespace app\admin\validate;

use think\Db;
use think\Validate;
use think\session;
use think\controller;

class User extends Validate {
    protected $rule = [
        ['username','require|max:50','用户名不能为空,请重新登录！！|用户名最大长度不超过50个字符'],
        ['password','require','密码不能为空,请重新登录！！'],
    ];

//    protected function checkLogin(){
//
//        $where = array(
//            'username' => Session::get('username'),
//            'key' => Session::get('key'),
//        );
//        $isLogin = Db::table('admin')->where($where)->find();
//        if ($isLogin){
//            $this->redirect('Index/index');
//        }else{
//            $this->redirect('Login/login');
//        }
//    }

}