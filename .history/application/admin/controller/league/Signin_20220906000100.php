<?php

namespace app\admin\controller\league;

use app\admin\model\league\League;
use app\common\controller\Backend;
use think\Db;

/**
 * 部门考勤
 *
 * @icon fa fa-circle-o
 */
class Signin extends Backend
{

    /**
     * Signin模型对象
     * @var \app\admin\model\league\Signin
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\league\Signin;
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                ->with(['user'])
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);
            foreach ($list as $row) {
                $result = League::where('id', 'in', $row->getdata('league_ids'))->column('name');
                $row->visible(['id', 'name', 'daterange', 'address', 'remark', 'league_ids']);
                $row->setAttr('league_ids', $result);
                $row->visible(['user']);
                $row->getRelation('user')->visible(['nickname']);
            }
            $result = array("total" => $list->total(), "rows" => $list->items());
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     *
     * @return string
     * @throws \think\Exception
     */
    public function add()
    {
        if (false === $this->request->isPost()) {
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);
        $this->dataLimit = 'auth';
        $this->dataLimitField = 'user_id';
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
            $result = $this->model->allowField(true)->save($params);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($result === false) {
            $this->error(__('No rows were inserted'));
        }
        $this->success();
    }

    public function signin($league_signin_id = '')
    {
        $list = $this->model
            ->with(['user', 'league'])
            ->where(['id' => $league_signin_id])
            ->select();
        foreach ($list as $row) {
            // $row->visible(['id', 'createtime', 'address', 'channel']);
            // $row->visible(['user']);
            $row->getRelation('user')->visible(['nickname']);
        }
        $this->view->assign("row", $row->toArray());
        return $this->view->fetch();
    }
}
