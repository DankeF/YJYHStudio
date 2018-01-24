<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2018/1/24
 * Time: 下午6:02
 */

namespace app\index\controller;

use think\Controller;
use think\View;
class Base extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->view = new View();
    }
}