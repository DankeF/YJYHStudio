<?php
namespace app\index\controller;


use think\Db;

class Index extends Base
{
    public function index()
    {

        $gallery = Db::table('gallery')
            ->where('gallery.is_delete=1 and title.is_delete=1')
            ->join('title', 'gallery.title_id=title.id')
            ->limit('18')
            ->select();
        $this->assign('gallery', $gallery);
        return $this->view->fetch();

    }
}
