<?php
/**
 * Created by PhpStorm.
 * User: dankef
 * Date: 2017/8/23
 * Time: 下午6:34
 */

namespace app\admin\controller;

use think\Session;
use think\Db;

class Index extends Base
{


    public function index()
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
        $this->assign('title', $album);
        return $this->fetch();
    }

    public function loginOut()
    {
        Session::set('usename', null);
        Session::set('key', null);
        $this->success('已退出，请重新登录!', '/admin/Login/login');
    }

}