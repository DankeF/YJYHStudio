<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2017/9/1
 * Time: 上午1:23
 */

namespace app\admin\controller;

//use app\admin\controller\Base;

use think\Db;
use think\View;

class Gallery extends Base
{
    public function gallery()
    {
        $view = new View();

        $gallery = Db::table('title')->select();
        $view->assign('gallery', $gallery);
        return $view->fetch();
    }


    public function detail()
    {
        $get = input('get.');
        $view = new View();

        $where = array(
            'title_id' => $get['id'],
        );
        $gallery_array = Db::table('gallery')
            ->field('t.name,g.id,g.path,g.title_id,g.add_time')
            ->alias('g')
            ->where($where)->join('title t', 'g.title_id = t.id')
            ->select();
        $view->assign('gallery', $gallery_array);
        return $view->fetch('detail');
    }

    public function upload()
    {
        $post = input('post.');

        $file = request()->file('image');

        $info = $file->validate(['size' => 10485760, 'ext' => 'jpg,png,git'])->move(ROOT_PATH . 'public' . DS . 'uploads');
        if ($info) {
            $path = $info->getSaveName();
            $data = array(
              "title_id" => 1,
              "path" => $path,
              "add_time" => date("Y-m-d H:i:s"),
            );
            $add_image = Db::table('gallery')->insert($data);
            if ($add_image){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 3;
        }
    }
}