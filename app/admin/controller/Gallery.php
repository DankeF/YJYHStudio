<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2017/9/1
 * Time: 上午1:23
 */

namespace app\admin\controller;


use think\Db;
use think\Image;
use think\View;
use think\File;

class Gallery extends Base
{
    //相册展示页
    public function gallery()
    {
        $view = new View();

        $title = Db::table('title')
            ->field('id,name,add_time')
            ->where('is_delete = 1')
            ->select();
        foreach ($title as $value) {
            $album[$value['id']]['id'] = $value['id'];
            $album[$value['id']]['name'] = $value['name'];
            $album[$value['id']]['add_time'] = $value['add_time'];
        }
        $view->assign('title', $title);

        $gallery = Db::table('gallery')
            ->field('title_id,count(title_id) as sum,upload,is_cover')
            ->where('is_delete = 1')
            ->group('upload')
            ->select();
        foreach ($gallery as $value) {
            if ($value['is_cover'] == '2') {
                $album[$value['title_id']]['upload'] = $value['upload'];
            }
            $album[$value['title_id']]['sum'] += $value['sum'];
        }
        $view->assign('gallery', $album);

        return $view->fetch();
    }

    //添加相册
    public function addGallery()
    {
        $get = input('get.');
        if ($get['name'] == '') {
            return 0;
        }
        $name = $get['name'];
        $title = Db::table('title')
            ->where("name='$name'")
            ->find();
        if ($title) {
            return 3;
        } else {
            $data = array(
                'id' => 2,
                'name' => $name,
                'add_time' => date('Y-m-d H:i:s')
            );
            $Res = Db::table('title')->insert($data);
            if ($Res) {
                return 1;
            } else {
                return 2;
            }
        }
    }

    //删除相册
    public function delGallery()
    {
        $get = input('get.');
        if (empty($get['id'])){
            return 0;
        }
        $id = $get['id'];
        $album = Db::table('title')
            ->where("id=$id")
            ->find();
        if (empty($album)){
            return 1;
        }
        $del = Db::table('title')
            ->where("id=$id")
            ->update(['is_delete' => '2']);
        if ($del){
            return 2;
        }else{
            return 3;
        }

    }

    //相册详情页
    public function detail()
    {
        $get = input('get.');
        $view = new View();

        $id = $get['id'];
        $where = array(
            'title_id' => $id,
            'g.is_delete' => '1'
        );
        $album = Db::table('title')
            ->where("id=$id")
            ->find();
        $gallery_array = Db::table('gallery')
            ->field('t.name,g.id,g.upload,g.title_id,g.add_time,g.is_cover')
            ->alias('g')
            ->where($where)->join('title t', 'g.title_id = t.id')
            ->select();
        $view->assign('title_id', $id);
        $view->assign('album', $album);
        $view->assign('gallery_array', $gallery_array);
        return $view->fetch('detail');
    }

    //上传图片
    public function upload()
    {
        $post = input('post.');
        $file = request()->file('image');
        $info = $this->validate(['image' => $file], ['image' => 'require|image']);
        if ($info !== true) {
            $error = json_decode($this->error('请选择图像文件'), true);
            return $error['msg'];
        } else {
            $fileName = time();
            $file = \think\Image::open($file);
            $imageName = $fileName . '.jpg';
            $path = date('Ymd') . DS . $imageName;
            if (is_dir(ROOT_PATH . 'public/uploads/' . date('Ymd'))) {
                $file->thumb(900, 900, Image::THUMB_SCALING)->save(ROOT_PATH . 'public/uploads/' . $path);

            } else {
                mkdir(ROOT_PATH . 'public/uploads/' . date('Ymd'));
                $file->thumb(900, 900, Image::THUMB_SCALING)->save(ROOT_PATH . 'public/uploads/' . $path);
            }
            $data = array(
                "title_id" => $post['title_id'],
                "upload" => $path,
                "add_time" => date("Y-m-d H:i:s"),
            );
            $add_image = Db::table('gallery')->insert($data);
            if ($add_image) {
                return 1;
            } else {
                return 2;
            }
        }

    }

    //删除图片
    public function delete()
    {
        $get = input('get.');

        $where = array(
            'id' => $get['id']
        );
        $del = Db::table('gallery')
            ->where($where)
            ->update(['is_delete' => '2']);

        if ($del) {
            return 1;
        } else {
            return 0;
        }
    }

    //设置相册封面
    public function cover()
    {
        $get = input('get.');

        $id = $get['id'];
        $title_id = $get['title_id'];
        $where = array(
            'id' => $id,
            'title_id' => $title_id
        );

        $is_cover = Db::table('gallery')->field('is_cover')->where($where)->find();

        if ($is_cover['is_cover'] == '1') {
            $cover = Db::table('gallery')
                ->where($where)
                ->update(['is_cover' => '2']);
            $cover = Db::table('gallery')
                ->where("id!=$id and title_id=$title_id")
                ->update(['is_cover' => '1']);
            if (!is_null($cover)) {
                return 1;
            } else {
                return 0;
            }
        } elseif ($is_cover['is_cover'] == '2') {
            $cover = Db::table('gallery')
                ->where($where)
                ->update(['is_cover' => '1']);

            if (!empty($cover)) {
                return 2;
            } else {
                return 0;
            }
        }


    }
}