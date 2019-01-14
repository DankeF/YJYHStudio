<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2018/1/26
 * Time: 下午5:18
 */

namespace app\index\controller;

use think\Db;
class About extends Base
{
    public function index(){

        $user_info = Db::table('admin')->select();

        $this->assign('userInfo', $user_info);
        return $this->fetch();
    }
}