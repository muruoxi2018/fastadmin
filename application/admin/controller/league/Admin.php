<?php

namespace app\admin\controller\league;

use app\admin\model\league\League;
use app\admin\model\league\League as LeagueModel;
use app\common\controller\Backend;
use \app\admin\model\league\Admin as LeagueAdminModel;
use \app\admin\model\league\AuthAdmin as AdminModel;
use fast\Random;
use fast\Tree;
use think\Db;
use think\Validate;


/**
 * 部门成员
 */
class Admin extends Backend
{

    protected $tree = null;
    /**
     * 部门人员对象
     * @var null
     */
    protected $dadminModel = null;
    protected $childrenGroupIds = []; //权限组
    /**
     * 部门所有
     * @var array
     */
    protected $allLeague = []; //


    public function _initialize()
    {
        parent::_initialize();
        $this->childrenGroupIds = $this->auth->getChildrenGroupIds($this->auth->isSuperAdmin());

        $this->dadminModel = new LeagueAdminModel;
        $leagueList = [];
        $this->allLeague = collection(LeagueModel::allLeague())->toArray();
        $leaguedata = [];
        foreach ($this->allLeague as $k => $v) {
            $state = ['opened' => true];
            $leagueList[] = [
                'id' => $v['id'],
                'parent' => $v['parent_id'] ? $v['parent_id'] : '#',
                'text' => __($v['name']),
                'state' => $state
            ];
        }
        $tree = Tree::instance()->init($this->allLeague, 'parent_id');
        $leagueOptions = $tree->getTree(0, "<option model='@model_id' value=@id @selected @disabled>@spacer@name</option>");

        $this->view->assign('leagueOptions', $leagueOptions);
        $this->assignconfig('leagueList', $leagueList);

        $result = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0));
        foreach ($result as $k => $v) {
            $leaguedata[$v['id']] = $v['name'];
        }
        $this->view->assign('leaguedata', $leaguedata);

        //兼容旧版本没有手机号
        $database = config('database');
        $exits_mobile = \think\Db::query("SELECT 1 FROM  information_schema.COLUMNS WHERE  table_name='{$database['prefix']}admin' AND COLUMN_NAME='mobile' limit 1");
        $this->view->assign('exits_mobile', $exits_mobile ? 1 : 0);
        $this->assignconfig('exits_mobile', $exits_mobile ? 1 : 0);
    }

    /**
     * 成员列表
     */
    public function index()
    {

        //测试
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        $league_id = $this->request->request("league_id");
        if ($this->request->isAjax()) {
            $this->model = new \app\admin\model\league\AuthAdmin();
            $filter = $this->request->get("filter", '');
            $filter = (array)json_decode($filter, true);
            $filter_w = [];

            if (isset($filter['league_id'])) {
                $league_id = $filter['league_id'];
                unset($filter['league_id']);
                $this->request->get(['filter' => json_encode($filter)]);
            }
            if ($league_id) {
                $user_ids = $this->dadminModel->where('league_id', 'in', $league_id)->column('user_id');
                $filter_w['id'] = ['in', $user_ids];
            }


            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->where($filter_w)
                ->order($sort, $order)->fetchSql(false)
                ->count();
            $list = $this->model
                ->where($where)
                ->with(['dadmin.league'])
                ->with(['groups.getGroup'])
                ->where($filter_w)
                ->order($sort, $order)
                ->limit($offset, $limit)->fetchSql(false)
                ->select();


            $result = array("total" => $total, "rows" => $list,"info"=>Db::getLastSql());

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     * @return string|\think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function add($ids = "")
    {
        $groupdata = LeagueModel::getGroupdata(
            $this->childrenGroupIds,
            $this->auth->isSuperAdmin() ? null : $this->auth->getGroups()
        );

        if ($this->request->isPost()) {

            $adminModel = new AdminModel();
            $leagueModel = new LeagueModel();

            $params = $this->request->post("row/a");

            if ($params) {
                Db::startTrans();
                try {

                    $league_id = $this->request->post("league_id/a");

                    //获取部门信息
                    if (!$league_id) {
                        exception(__("部门不能为空"));
                    }
                    $d_list = $leagueModel->where('id', 'in', $league_id)->select();
                    if (!$d_list) {
                        exception(__("部门不能为空"));
                    }

                    // if (!Validate::is($params['password'], '\S{6,16}')) {
                    //     exception(__("Please input correct password"));
                    // }
                    // $params['salt'] = Random::alnum();
                    // $params['password'] = md5(md5($params['password']) . $params['salt']);
                    // $params['avatar'] = '/assets/img/avatar.png'; //设置新管理员默认头像。
                    // $result = $adminModel->validate('User.add')->save($params);
                    // if ($result === false) {
                    //     exception($adminModel->getError());
                    // }

                    $user_id = $params['user_id'];
                    //判断用户是否存在
                    $user = $adminModel::get(['id' => $user_id]);
                    if (!$user) {
                        exception($user);
                    }
                    $this->dadminModel->where('user_id', $user_id)->delete();
                    $dadmin = array();
                    //添加部门信息
                    foreach ($d_list as $d_row) {
                        $dadmin[] = ['league_id' => $d_row->id, 'user_id' => $user_id];
                    }

                    $this->dadminModel->saveAll($dadmin);
                    // $group = $this->request->post("group/a");
                    // //过滤不允许的组别,避免越权
                    // $group = array_intersect($this->childrenGroupIds, $group);

                    // //添加权限默认第一个部门
                    // if (!$group) {
                    //     exception(__('The parent group exceeds permission limit'));
                    // }

                    // $dataset = [];
                    // foreach ($group as $value) {
                    //     $dataset[] = ['uid' => $user_id, 'group_id' => $value];
                    // }
                    // model('AuthGroupAccess')->saveAll($dataset);


                    Db::commit();
                } catch (\Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                $this->success();
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign('groupdata', $groupdata);
        return $this->view->fetch();
    }

    /**
     * 修改
     * @param null $ids
     * @return string|\think\response\Json
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit($ids = null)
    {
        $this->model = new AdminModel();
        $leagueModel = new LeagueModel();
        $row = $this->model->get($ids);
        if ($this->request->isPost()) {

            //判断是不是超级管理员编辑
            if (!LeagueAdminModel::isSuperAdmin($this->auth->id) && LeagueAdminModel::isSuperAdmin($row->id)) {
                $this->error("您无权操作超级管理员");
            }


            Db::startTrans();
            try {
                $league_id = $this->request->post("league_id/a");

                //获取部门信息
                if (!$league_id) {
                    exception(__("League can't null"));
                }
                $d_list = $leagueModel->where('id', 'in', $league_id)->select();

                if (!$d_list) {
                    exception(__("League can't null"));
                }

                $this->dadminModel->where('user_id', $row->id)->delete();
                $exist_leagueids = $this->dadminModel->where('user_id', $row->id)->column('league_id');
                $dadmin = array();
                $deleteids = array_diff($exist_leagueids, $league_id);
                //添加部门信息
                foreach ($d_list as $d_row) {
                    if (!in_array($d_row->id, $exist_leagueids)) {
                        $dadmin[] = ['league_id' => $d_row->id,'user_id'=>$ids];
                    }
                }
                if ($deleteids) {
                    $this->dadminModel->where('user_id', $row->id)->where('league_id', 'in', $deleteids)->delete();
                }
                if (count($dadmin) > 0) {
                    $this->dadminModel->saveAll($dadmin);
                }

                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            $this->success();
        }

        $groupdata = LeagueModel::getGroupdata(
            $this->childrenGroupIds,
            $this->auth->isSuperAdmin() ? null : $this->auth->getGroups()
        );

        $grouplist = $this->auth->getGroups($row['id']);
        $groupids = [];
        foreach ($grouplist as $k => $v) {
            $groupids[] = $v['id'];
        }
        $this->view->assign("groupids", $groupids);

        $this->view->assign('row', $row);
        $this->view->assign('league_ids', $this->dadminModel->getLeagueIds($ids));
        $this->view->assign('groupdata', $groupdata);

        return $this->view->fetch();
    }


    /**
     * 删除
     */
    public function del($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $this->childrenAdminIds = $this->auth->getChildrenAdminIds($this->auth->isSuperAdmin());
        $this->model = new AdminModel();

        $ids = $ids ? $ids : $this->request->post("ids");
        if ($ids) {
            $ids = array_intersect($this->childrenAdminIds, array_filter(explode(',', $ids)));
            // 避免越权删除管理员
            $childrenGroupIds = $this->childrenGroupIds;
            $adminList = $this->model->where('id', 'in', $ids)->where('id', 'in', function ($query) use ($childrenGroupIds) {
                $query->name('auth_group_access')->where('group_id', 'in', $childrenGroupIds)->field('uid');
            })->select();
            if ($adminList) {
                $deleteIds = [];
                foreach ($adminList as $k => $v) {
                    $deleteIds[] = $v->id;
                }
                $deleteIds = array_values(array_diff($deleteIds, [$this->auth->id]));
                if ($deleteIds) {
                    Db::startTrans();
                    try {
                        $this->model->destroy($deleteIds);
                        model('AuthGroupAccess')->where('uid', 'in', $deleteIds)->delete();
                        //删除部门员工信息
                        $this->dadminModel->where('user_id', 'in', $deleteIds)->delete();;

                        Db::commit();
                    } catch (\Exception $e) {
                        Db::rollback();
                        $this->error($e->getMessage());
                    }
                    $this->success();
                }
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('You have no permission'));
    }

    /**
     * 批量更新
     * @internal
     */
    public function multi($ids = "")
    {
        // 管理员禁止批量操作
        $this->error();
    }

    /**
     * 设置部门负责人
     */
    public function principal($ids = "")
    {

        $this->model = new AdminModel();
        $leagueModel = new LeagueModel();
        $row = $this->model->get($ids);
        if ($this->request->isPost()) {

            //判断是不是超级管理员编辑
            if (!LeagueAdminModel::isSuperAdmin($this->auth->id) && LeagueAdminModel::isSuperAdmin($row->id)) {
                $this->error("您无权操作超级管理员");
            }

            $league_id = $this->request->post("league_id/a");
            if ($league_id && $league_id[0]) {
                Db::startTrans();
                try {
                    $d_list = $leagueModel->where('id', 'in', $league_id)->select();

                    if (!$d_list) {
                        exception(__("League can't null"));
                    }
                    //先移除他所有负责的部门
                    $this->dadminModel->where('user_id', $row->id)->update(['is_principal' => 0]);

                    //判断选择的部门是否存在，不存在就先把他加入
                    $p_leagueids = array_column($d_list, 'id');
                    $exist_leagueids = $this->dadminModel->where('user_id', $row->id)->column('league_id');

                    $dadmin = array();
                    //添加部门信息
                    foreach ($d_list as $d_row) {
                        if (!in_array($d_row->id, $exist_leagueids)) {
                            $dadmin[] = ['league_id' => $d_row->id, 'organise_id' => $d_row->organise_id ? $d_row->organise_id : $d_row->id, 'user_id' => $row->id];
                        }
                    }
                    if (count($dadmin) > 0) {
                        $this->dadminModel->saveAll($dadmin);
                    }
                    //更改为负责人
                    $this->dadminModel->where('user_id', $row->id)->where('league_id', 'in', $p_leagueids)->update(['is_principal' => 1]);


                    Db::commit();
                } catch (\Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
            } else {
                //移除他所有负责的部门
                $this->dadminModel->where('user_id', $row->id)->update(['is_principal' => 0]);
            }
            $this->success();
        }


        $this->view->assign('row', $row);
        $this->view->assign('league_ids', $this->dadminModel::getPrincipalIds($ids));
        return $this->view->fetch();
    }

    /**
     * 验证用户是否存在
     */
    public function checkid($id = "")
    {

        if ($this->request->isAjax()) {
            $adminModel = new AdminModel();

            $user_id = $this->request->param('user_id');

            $user = $adminModel::get(['id' => $user_id]);
            if ($user) {
                $this->success('用户昵称：' . $user->nickname);
            } else {
                $this->error('无此用户');
            }
        }
    }
}
