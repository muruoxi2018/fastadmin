<?php

namespace app\admin\controller\league;

use app\common\controller\Backend;
use think\Db;
use app\admin\model\league\League;
use app\admin\model\league\Signin;
use app\admin\model\league\Admin;
use app\common\model\User;

/**
 * 
 *
 * @icon fa fa-circle-o
 */
class Signinfo extends Backend
{

    /**
     * Signinfo模型对象
     * @var \app\admin\model\league\Signinfo
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\league\Signinfo;
        $this->view->assign("channelList", $this->model->getChannelList());
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    public function index()
    {
        $this->view->assign('row', '');
        return $this->view->fetch();
    }
    /**
     * @DateTime 2022-09-06
     * 获取已签到用户
     *
     * @return void
     */
    public function signed()
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
                $row->visible(['id', 'createtime', 'address', 'channel']);
                $row->visible(['user']);
                $row->getRelation('user')->visible(['nickname']);
            }
            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch('index');
    }

    /**
     * @DateTime 2022-09-06
     * 获取未签到用户
     *
     * @return void
     */
    public function unsigned()
    {
        $this->request->filter(['strip_tags']);
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
            // 加入未签到用户
            $filter = $this->request->get("filter", '');
            $filter = (array)json_decode($filter, true);
            $league_signin_id = $filter["league_signin_id"];
            $ids = Signin::get($league_signin_id)->value("league_ids");
            $ids = explode(',', $ids);
            $child_ids = [];
            foreach ($ids as $id) {
                $child_ids += League::getChildrenIds($id);
            }
            $all_ids = Admin::getLeagueAdminIds($child_ids);
            $unsign_ids = array();
            $sign_ids = array();
            $unsign_user = array();
            foreach ($list as $row) {
                array_push($sign_ids, $row->getdata('user_id'));
            }
            foreach ($all_ids as $v) {
                if (!in_array($v, $sign_ids)) {
                    $user = User::get($v)->append([], true)->visible(['nickname', 'mobile'])->toArray();
                    array_push($unsign_user, $user);
                }
            }
            $result = array("total" => count($unsign_user), "rows" => $unsign_user);
            return json($result);
        }
        return $this->view->fetch('index');
    }



    /**
     * @DateTime 2022-09-06
     * 添加签到信息
     *
     * @param 签到ID $league_signin_id
     * @param 渠道 $channel
     * @param 地点 $address
     * @param 用户ID $user_id
     * @return void
     */
    public function add($league_signin_id = '', $channel = 'Manual', $address = '', $user_id = null)
    {
        if (false === $this->request->isPost()) {
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        $params['league_signin_id'] = $league_signin_id;
        $params['channel'] = $channel;
        $params['address'] = $address;
        if ($user_id) $params['user_id'] = $user_id;

        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }

        $params = $this->preExcludeFields($params);

        $info = $this->model->get(['user_id' => $params['user_id']]);
        if ($info) {
            $this->error(__('您已签到，请勿重复签到'));
        }
        $daterange = Signin::get($league_signin_id)->value('daterange');
        $date = explode(' - ', $daterange);
        if(strtotime(time())>strtotime($date[0])){
            $this->error(__('签到尚未开始！'));
        }
        if(strtotime(time())<strtotime($date[1])){
            $this->error(__('签到已经停止！'));
        }
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
        $this->success('签到成功！');
    }


    public function share($league_signin_id = '')
    {
        $this->view->assign("url", request()->domain() . url("/league/signinfo/signin/league_signin_id/{$league_signin_id}"));
        return $this->view->fetch();
    }

    public function signin($league_signin_id = '')
    {
        if ($this->request->isPost()) {
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
                $channel = 'WeChat';
                $address = '';
                $this->add($league_signin_id, $channel, $address, $this->auth->id);
            } else {
                $this->error('请在微信中签到！');
            }
        }
        $row = \app\admin\model\league\Signin::get(['id', $league_signin_id]);
        $league_ids = \app\admin\model\league\League::where('id', 'in', $row->getdata('league_ids'))->column('name');
        $nickname = \app\common\model\User::where($row->getAttr('user_id'))->value('nickname');
        $row->setAttr('league_ids', implode(', ', $league_ids));
        $row->setAttr('nickname', $nickname);
        $row->visible(['nickname', 'name', 'daterange', 'address', 'remark', 'league_ids']);
        $this->view->assign("row", $row->toArray());
        return $this->view->fetch();
    }
}
