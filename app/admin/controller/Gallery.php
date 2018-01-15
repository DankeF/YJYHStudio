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
    public function gallery()
    {
        $view = new View();

        $title = Db::table('title')
            ->field('id,name,add_time')
            ->where('is_delete = 1')
            ->select();
        $view->assign('title', $title);

        $gallery = Db::table('gallery')
            ->field('title_id,count(title_id) as sum,upload,is_cover')
            ->where('is_delete = 1')
            ->group('is_cover')
            ->select();
        foreach ($title as $value) {
            foreach ($gallery as $item) {
                if ($value['id'] == $item['title_id']) {
                    $data[$value['id']]['title_id'] = $value['id'];
                    $data[$value['id']]['upload'] = $item['upload'];
                    $data[$value['id']]['sum'] += $item['sum'];
                }
            }
        }
        $view->assign('gallery', $data);

        return $view->fetch();
    }


    public function detail()
    {
        $get = input('get.');
        $view = new View();

        $where = array(
            'title_id' => $get['id'],
            'g.is_delete' => '1'
        );
        $gallery_array = Db::table('gallery')
            ->field('t.name,g.id,g.upload,g.title_id,g.add_time,g.is_cover')
            ->alias('g')
            ->where($where)->join('title t', 'g.title_id = t.id')
            ->select();
        $view->assign('title_id', $get['id']);
        $view->assign('gallery_array', $gallery_array);
        return $view->fetch('detail');
    }

    public function upload()
    {
        $post = input('post.');
        $file = request()->file('image');
        $info = $this->validate(['image' => $file], ['image' => 'require|image']);
        if ($info !== true) {
            $error = json_decode($this->error('请选择图像文件'),true);
            echo $error['msg'];
        } else {
            $fileName = time();
            $file = \think\Image::open($file);
            $imageName = $fileName . '.png';
            $path = date('Ymd') . DS . $imageName;
            if (is_dir(ROOT_PATH . 'public/uploads/' . date('Ymd')))
            {
                $file->thumb(150, 150, Image::THUMB_SCALING)->save(ROOT_PATH . 'public/uploads/' . $path);

            }else{
                mkdir(ROOT_PATH . 'public/uploads/' . date('Ymd'));
                $file->thumb(150, 150, Image::THUMB_SCALING)->save(ROOT_PATH . 'public/uploads/' . $path);
            }
            $data = array(
                "title_id" => $post['title_id'],
                "upload" => $path,
                "add_time" => date("Y-m-d H:i:s"),
            );
            $add_image = Db::table('gallery')->insert($data);
            if ($add_image) {
                echo 1;
            } else {
                echo 2;
            }
        }

    }

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
            echo 1;
        } else {
            echo 0;
        }
    }

    public function cover()
    {
        $get = input('get.');

        $where = array(
            'id' => $get['id']
        );

        $is_cover = Db::table('gallery')->field('is_cover')->where($where)->find();

        if ($is_cover['is_cover'] == '1') {
            $cover = Db::table('gallery')
                ->where($where)
                ->update(['is_cover' => '2']);
            $cover = Db::table('gallery')
                ->where('id', 'NEQ', $get['id'])
                ->update(['is_cover' => '1']);
            if (!is_null($cover)) {
                echo 1;
            } else {
                echo 0;
            }
        } elseif ($is_cover['is_cover'] == '2') {
            $cover = Db::table('gallery')
                ->where($where)
                ->update(['is_cover' => '1']);

            if (!empty($cover)) {
                echo 2;
            } else {
                echo 0;
            }
        }


    }
}