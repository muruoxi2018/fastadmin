<?php

namespace app\index\controller;

use app\common\controller\Frontend;

class Index extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = 'default';

    public function index()
    {
        
        $this->view->title = '首页';
        return $this->view->fetch();
    }

    // public function intro(){
    //     $this->view->title = '关于我们';
    //     return $this->view->fetch();
    // }

}
