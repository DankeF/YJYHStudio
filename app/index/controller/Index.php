<?php
namespace app\index\controller;


use think\Db;

class Index extends Base
{
    public function index()
    {

        $gallery = Db::table('gallery')
            ->where('gallery.is_delete=1 and title.is_delete=1 and title_id != 0')
            ->join('title', 'gallery.title_id=title.id')
            ->order('rand()')
            ->limit('18')
            ->select();
        $this->assign('gallery', $gallery);

        $big_pic = Db::table('gallery')
            ->where('is_cover=2 and title_id=0')
            ->find();
        $this->assign('big_pic', $big_pic);
        return $this->view->fetch();

    }

}
