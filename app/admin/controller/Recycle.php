<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2018/1/19
 * Time: 下午5:34
 */

namespace app\admin\controller;


use think\View;
use think\Db;

class recycle extends Base
{
    public function Gallery()
    {
        $view = new View();
        $album = Db::table('title')
            ->where('is_delete=2')
            ->select();
        $view->assign('album', $album);
        return $view->fetch();
    }

    //还原相册
    public function restore()
    {

        $get = input('get.');
        if (empty($get['id'])) {
            return 0;
        }
        $id = $get['id'];
        $album = Db::table('title')
            ->where("id=$id")
            ->find();
        if (empty($album)) {
            return 1;
        }
        $del = Db::table('title')
            ->where("id=$id")
            ->update(['is_delete' => '1']);
        if ($del) {
            return 2;
        } else {
            return 3;
        }

    }

    //彻底删除相册
    public function delGallery()
    {
        $get = input('get.');
        if (empty($get['id'])) {
            return 0;
        }
        $id = $get['id'];
        $album = Db::table('title')
            ->where("id=$id")
            ->find();
        if (empty($album)) {
            return 1;
        }
        $del = Db::table('title')
            ->where("id=$id")
            ->delete();
        if ($del) {
            return 2;
        } else {
            return 3;
        }
    }

    public function photo(){
        $view = new View();

        $photo = Db::table('gallery')
            ->where('is_delete=2')
            ->select();

        $view->assign('photo', $photo);
        $view->fetch();
    }

    public function restorePhoto(){

    }
}