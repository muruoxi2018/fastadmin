<?php

namespace app\admin\controller\ygame;

use app\common\controller\Backend;

/**
 * 组别管理
 *
 * @icon fa fa-circle-o
 */
class Group extends Backend
{
    
    /**
     * Group模型对象
     * @var \app\admin\model\ygame\Group
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\ygame\Group;

        $project_id = $this->request->get('project_id');
        $this->assign('project_id',$project_id);
    }

    /**
     * 查看列表
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
            $project_id = $this->request->param('project_id');
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $this->model
                ->where($where)
                ->where(['project_id'=>$project_id])
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->where(['project_id'=>$project_id])
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            $list = collection($list)->toArray();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }
}
