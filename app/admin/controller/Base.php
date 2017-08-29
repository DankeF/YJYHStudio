<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2017/8/25
 * Time: 下午6:40
 */

namespace app\admin\controller;

use think\controller;
use think\Request;

class Base extends controller
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }
}