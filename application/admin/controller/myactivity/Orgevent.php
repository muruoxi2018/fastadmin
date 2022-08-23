<?php

namespace app\admin\controller\myactivity;

use app\common\controller\Backend;


/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Orgevent extends Backend
{

    /**
     * Orgevent模型对象
     * @var \app\admin\model\Orgevent
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Orgevent;
        $this->view->assign("statusList", $this->model->getStatusList());
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */



    /**
     * 导出秩序册
     *
     * @param $ids
     * @return void
     */
    public function export($ids = null)
    {
        if (false === $this->request->isAjax()) {
            $this->error(__('Invalid parameters'));
        }
        $ids = $ids ?: $this->request->post('ids');
        if (empty($ids)) {
            $this->error(__('Parameter %s can not be empty', 'ids'));
        }


        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }

        $search = array(
            '{比赛名称}',
            '{比赛地点}',
            '{比赛时间}',
            '{指导单位}',
            '{主办单位}',
            '{承办单位}',
            '{协办单位}',
            '{赞助单位}',
            '{竞赛时间和地点}',
            '{竞赛项目}',
            '{参赛资格}',
            '{参赛办法}',
            '{竞赛办法}',
            '{纪律要求}',
            '{竞赛组委会}',
            '{录取名次和奖励办法}',
            '{报名和报到}',
            '{经费保障}'
        );
        $data = $this->model
        ->where('id',$ids)
        ->find();

        $replace = array(
            $data->name, 
            $data->address,
            $data->daterange,
            $data->zhidao,
            $data->zhuban,
            $data->chengban,
            $data->xieban,
            $data->zanzhu,
            $data->timeandaddress,
            $data->xiangmu,
            $data->zige,
            $data->cansaibanfa,
            $data->jingsaibanfa,
            $data->jilv,
            $data->zuweihui,
            $data->luquandjiangli,
            $data->baomingandbaodao,
            $data->jingfei
        );
        // var_dump($matchs);
        // $this->error('暂无数据','',$data);
        $template = include ADDON_PATH . 'myactivity' . DS . 'data' . DS . 'zhixuce.php';
        $file = str_replace($search, $replace, $template);
        $this->success('即将开始下载', null, $file);
    }
    
}
