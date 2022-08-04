<?php

namespace app\index\controller;

use app\common\controller\Frontend;

class Article extends Frontend
{

    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = 'layout_index';

    public function index()
    {
        $this->request->has('articelId') ? $articelId = $this->request->param('articelId') : $articelId = 1;
        //$this->error($articelId, '', $articelId);
        $model = new \app\common\model\Article();
        //$article = $model->getOne($articelId);
        $where = array();
        $where['id'] = $articelId;
        $article = $model
            ->where($where)
            ->find($articelId);

        if (!$article) {
            $this->error('文章不存在');
        }
        $model->where($where)->setInc('views');
        $article->createtime = human_date($article->createtime);
        // $article->category = $article->category->name;
        //$article
        $this->assign('article', $article);
        $this->view->title = '首页';
        return $this->view->fetch();
    }
}
