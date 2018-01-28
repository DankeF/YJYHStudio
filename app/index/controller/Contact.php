<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2018/1/26
 * Time: 上午10:36
 */

namespace app\index\controller;


class Contact extends Base
{
    public function index(){
        return $this->fetch();
    }
}