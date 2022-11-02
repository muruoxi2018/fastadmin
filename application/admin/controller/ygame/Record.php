<?php

namespace app\admin\controller\ygame;

use app\common\controller\Backend;

/**
 * 报名详情管理
 *
 * @icon fa fa-circle-o
 */
class Record extends Backend
{
    
    /**
     * Record模型对象
     * @var \app\admin\model\ygame\Record
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\ygame\Record;

    }

    public function import()
    {
        parent::import();
    }

    /**
     * 查看列表
     */
    public function index()
    {
        if ($this->request->isAjax())
        {
            $type = $this->request->param("type",1);
            $project_id = $this->request->param('project_id');
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $total = $this->model
                ->alias("record")

                ->where($where)
                ->where(['record.project_id'=>$project_id,'record.status'=>1,'record.type'=>$type])
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->alias("record")
                ->field("record.*,group.group_name,team.team_name,team.leader,team.mobile as team_mobile")
                ->join("ygame_group group","record.group_id=group.id","left")
                ->join("ygame_team team","record.team_id=team.id","left")
                ->where($where)
                ->where(['record.project_id'=>$project_id,'record.status'=>1,'record.type'=>$type])
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
