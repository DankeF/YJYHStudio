<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2018/1/28
 * Time: 下午8:24
 */

namespace app\admin\controller;


use think\Session;

class Loginout extends Base
{
    public function Out()
    {
        Session::clear();

        $this->success('请先登录!', '/admin/Login/login');
    }
}