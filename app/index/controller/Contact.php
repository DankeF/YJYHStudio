<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2018/1/26
 * Time: 上午10:36
 */

namespace app\index\controller;

use think\Db;
class Contact extends Base
{
    public function index(){

        $contact = Db::table('admin')->where('id = 1')->find();
        $this->assign('contact', $contact);
        return $this->fetch();
    }
}