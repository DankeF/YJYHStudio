<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2017/8/24
 * Time: 下午5:23
 */

namespace app\admin\controller;

use think\view;
use think\session;
class User
{
    public function alterAdmin(){
        $username = Session::get('username');
    }
}