<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2017/9/1
 * Time: 上午1:23
 */

namespace app\admin\controller;

//use app\admin\controller\Base;

use think\View;

class Gallery extends Base
{
    public function gallery()
    {
        $view = new View();

        return $view->fetch();
    }
}