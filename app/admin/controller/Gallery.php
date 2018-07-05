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
use think\Request;

class Gallery extends Base
{

    //相册展示页
    public function gallery()
    {

        $title = Db::table('title')
            ->field('id,name,add_time')
            ->where('is_delete = 1 and id!=0')
            ->select();
        foreach ($title as $value) {
            $album[$value['id']]['id'] = $value['id'];
            $album[$value['id']]['name'] = $value['name'];
            $album[$value['id']]['add_time'] = $value['add_time'];
        }
        $this->assign('title', $title);

        $gallery = Db::table('gallery')
            ->field('title_id,count(title_id) as sum,upload,is_cover')
            ->where('is_delete = 1 and title_id!=0')
            ->group('upload')
            ->select();
        foreach ($gallery as $value) {
            if ($value['is_cover'] == '2') {
                $album[$value['title_id']]['upload'] = $value['upload'];
            }
            $album[$value['title_id']]['sum'] += $value['sum'];
        }
        $this->assign('gallery', $album);

        return $this->fetch();
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

    //编辑相册
    public function edit()
    {
        $get = input('get.');
        if (empty($get['id'])){
            return 0;
        }
        $id = $get['id'];
        $name = $get['name'];
        $album = Db::table('title')
            ->where("name='$name'")
            ->find();
        if ($album) {
            return 3;
        }else{
            $where = array(
                'id' => $id,
            );
            $Res = Db::table('title')
            ->where($where)
            ->update(['name' => $name]);
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
        $is_photo = Db::table('gallery')
            ->where("id=$id")
            ->find();
        if (!empty($is_photo)) {
            return 4;
        }
        $del = Db::table('title')
            ->where("id=$id")
            ->update(['is_delete' => '2']);
        if ($del) {
            return 2;
        } else {
            return 3;
        }

    }

    //相册详情页
    public function detail()
    {
        $get = input('get.');

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
        $this->assign('title_id', $id);
        $this->assign('album', $album);
        $this->assign('gallery_array', $gallery_array);
        return $this->fetch('detail');
    }

    //上传图片
    public function upload()
    {
        $post = input('post.');
        $file = request()->file("image");
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

    //设置首页大图
    public function bigPic()
    {

        $id = 0;
        $where = array(
            'title_id' => $id,
            'is_delete' => '1',
        );
        $big_pic = Db::table('gallery')
            ->field('id,upload,add_time,is_cover')
            ->where($where)
            ->select();
        $this->assign('big_pic', $big_pic);
        return $this->fetch('bigPic');
    }

    //上传首页大图
    public function uploadBig()
    {
        $post = input('post.');
        $file = request()->file('image');
        $info = $this->validate(['image' => $file], ['image' => 'require|image']);

        if ($info !== true) {
            $error = json_decode($this->error('请选择图像文件'), true);
            return $error['msg'];
        } else {
            $img = $file->getInfo();
            $fileName = $img['name'];
            $file = \think\Image::open($file);
            $path = "bigPic/" . $fileName;
            if (is_dir(ROOT_PATH . 'public/uploads/bigPic')) {
                $file->thumb(1700, 1700, Image::THUMB_SCALING)->save(ROOT_PATH . 'public/uploads/' . $path, null, 100, true);

            } else {
                mkdir(ROOT_PATH . 'public/uploads/bigPic');
                $file->thumb(1700, 1700, Image::THUMB_SCALING)->save(ROOT_PATH . 'public/uploads/' . $path, null, 100, true);
            }
            $data = array(
                "title_id" => $post['title_id'],
                "upload" => $path,
                "is_cover" => '2',
                "add_time" => date("Y-m-d H:i:s"),
            );
            Db::table('gallery')->where('title_id=0')->update('is_cover=1');
            $add_image = Db::table('gallery')->insert($data);
            if ($add_image) {
                return 1;
            } else {
                return 2;
            }
        }
    }
}