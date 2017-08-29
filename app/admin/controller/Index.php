<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2017/8/23
 * Time: 下午6:34
 */
namespace app\admin\controller;

use think\Validate;
use think\View;
use think\controller;
use app\admin\controller\Login;

class Index extends controller{
    public function index(){

        $validate = new Validate();

        $view = new View();
        return $view->fetch();
    }
}