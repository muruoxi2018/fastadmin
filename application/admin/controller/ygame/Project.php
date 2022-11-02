<?php

namespace app\admin\controller\ygame;

use app\common\controller\Backend;

/**
 * 赛事管理
 *
 * @icon fa fa-circle-o
 */
class Project extends Backend
{
    
    /**
     * Project模型对象
     * @var \app\admin\model\ygame\Project
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\ygame\Project;

    }


}
