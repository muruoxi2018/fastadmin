<?php

namespace app\admin\controller\myactivity;

use app\common\controller\Backend;
use think\Db;
use think\View;
use think\exception\ValidateException;
use think\exception\PDOException;
use Exception;
use think\Cookie;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Achievement extends Backend
{

    /**
     * Achievement模型对象
     * @var \app\admin\model\Achievement
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Achievement;
        $this->view->assign("sexList", $this->model->getSexList());
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    /**
     * 查看
     *
     * @return string|Json
     * @throws \think\Exception
     * @throws DbException
     */
    public function index()
    {
        //Cookie::set('orgevent_id', $this->request->param('ids'),3600);
        $orgevent_id = $this->request->param('orgevent_id');
        Cookie::set('orgevent_id', $orgevent_id);
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if (false === $this->request->isAjax()) {
            return $this->view->fetch();
        }
        //如果发送的来源是 Selectpage，则转发到 Selectpage
        if ($this->request->request('keyField')) {
            return $this->selectpage();
        }
        [$where, $sort, $order, $offset, $limit] = $this->buildparams();
        $list = $this->model
        ->with('orgevent')
            ->where($where)
            ->where('orgevent_id',$orgevent_id)
            ->order($sort, $order)
            ->paginate($limit);
        $result = ['total' => $list->total(), 'rows' => $list->items()];
        return json($result);
    }


    /**
     * 获取现有组别
     */
    public function getGroup()
    {
        // //当前是否为关联查询
        // $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->column('group');
            //->paginate($limit);

            $result = array("query" => $this->request->param('query'), "suggestions" => $list);

            return json($result);
        }
    }

    /**
     * 添加
     *
     * @return string
     * @throws \think\Exception
     */
    public function add()
    {
        if (!Cookie::has('orgevent_id')) {
            $this->error(__('页面已过期，请重新访问。', ''));
        }
        if (false === $this->request->isPost()) {
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);

        if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
            $params[$this->dataLimitField] = $this->auth->id;
        }
        $result = false;
        Db::startTrans();
        try {
            //是否采用模型验证
            if ($this->modelValidate) {
                $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                $this->model->validateFailException()->validate($validate);
            }
            $params['orgevent_id'] = Cookie::get('orgevent_id');
            $bianhao = $this->model->where('orgevent_id', $params['orgevent_id'])->max('number') + 1;
            $params['number'] = $bianhao;
            $result = $this->model->allowField(true)->save($params);
            Db::commit();
        } catch (ValidateException | PDOException | Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($result === false) {
            $this->error(__('No rows were inserted'));
        }
        $this->success();
    }
    
    /**创建比赛 */
    public function create(){
        if (false === $this->request->isAjax()) {
            $this->error();
        }
        $orgevent_id = $this->request->post('orgevent_id');
        if (empty($orgevent_id)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        
    }
}
