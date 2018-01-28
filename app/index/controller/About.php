<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2018/1/26
 * Time: 下午5:18
 */

namespace app\index\controller;


class About extends Base
{
    public function index(){
        return $this->fetch();
    }
}