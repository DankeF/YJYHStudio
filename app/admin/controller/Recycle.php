<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2018/1/19
 * Time: 下午5:34
 */

namespace app\admin\controller;


use think\Db;

class recycle extends Base
{
    public function Gallery()
    {
        $album = Db::table('title')
            ->where('is_delete=2')
            ->select();
        $this->assign('album', $album);
        return $this->fetch();
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

    public function photo()
    {

        $photo = Db::table('gallery')
            ->field('title.name,gallery.add_time,gallery.id,gallery.upload')
            ->where('gallery.is_delete=2')
            ->join('title', 'gallery.title_id=title.id')
            ->select();
        $this->assign('photo', $photo);
        return $this->fetch();
    }

    //还原图片
    public function restorePhoto()
    {
        $get = input('get.');
        if (empty($get['id'])) {
            return 0;
        }
        $id = $get['id'];
        $album = Db::table('gallery')
            ->where("id=$id")
            ->find();
        if (empty($album)) {
            return 1;
        }
        $del = Db::table('gallery')
            ->where("id=$id")
            ->update(['is_delete' => '1']);
        if ($del) {
            return 2;
        } else {
            return 3;
        }
    }

    //彻底删除图片
    public function delPhoto(){
        $get = input('get.');
        if (empty($get['id'])) {
            return 0;
        }
        $id = $get['id'];
        $photo = Db::table('gallery')
            ->where("id=$id")
            ->find();
        if (empty($photo)) {
            return 1;
        }
        $del = Db::table('gallery')
            ->where("id=$id")
            ->delete();
        if ($del) {
            return 2;
        } else {
            return 3;
        }
    }
}