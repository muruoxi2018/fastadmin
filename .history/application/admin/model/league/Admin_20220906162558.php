<?php

namespace app\admin\model\league;

use app\admin\model\league\League as LeagueModel;
use fast\Tree;
use think\Db;
use think\Exception;
use think\Model;

class Admin extends Model
{
    // 表名
    protected $name = 'league_admin';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    /**
     * 关联部门
     */
    public function league()
    {
        return $this->hasOne('app\admin\model\league\League', 'id', 'league_id');
    }

    /**
     * 获取指定部门的员工
     * @param $admin_id
     * @param bool $is_principal 是否取负责部门
     * @return array|bool|string
     */
    public static function getLeagueAdminIds($leagueids)
    {
        //获取当前部门负责人
        $AdminIds = Db::name('league_admin')
            ->alias('da')
            ->join('__' . strtoupper('league') . '__ d', 'da.league_id = d.id')
            ->where('da.league_id', 'in', $leagueids)
            ->where('d.status', 'normal')
            ->column('da.user_id');
        return $AdminIds;
    }

    /**
     * 获取员工者的部门ids
     * @param $admin_id
     * @param bool $is_principal 是否取负责部门
     * @return array|bool|string
     */
    public static function getLeagueIds($admin_id, $is_principal = false)
    {
        $model = new self();
        if ($is_principal) $model->where('is_principal', 1);
        return $model->where('user_id', $admin_id)->column('league_id');
    }


    /**
     * 获取负责的部门IDs
     * @param $admin_id
     * @return array|bool|string
     */
    public static function getPrincipalIds($admin_id)
    {
        return self::where('user_id', $admin_id)->where('is_principal', 1)->column('league_id');
    }

    /**
     * 获取组织(公司)ids
     * @param $admin_id
     * @param int $is_principal 是否只获取负责的部门
     * @return array|bool|string
     */
    public static function getOrganiseIds($admin_id, $is_principal = 0)
    {
        $where = array();
        if ($is_principal) $where['is_principal'] = 1;

        return self::where('user_id', $admin_id)->where($where)->column('organise_id');
    }


    /**
     * 当前负责人下属ids
     * @param int $admin_id 某个管理员ID
     * @param boolean $withself 是否包含自身
     * @param string $league_ids 是否指定某个管理部门id，多个逗号id隔开
     * @return array
     */
    public static function getChildrenAdminIds($admin_id, $withself = false, $league_ids = null)
    {
        $childrenAdminIds = [];
        if (self::isSuperAdmin($admin_id)) {
            $childrenAdminIds = \app\admin\model\league\AuthAdmin::column('id');
        } else {
            $leagueIds = self::getChildrenLeagueIds($admin_id, true);
            $authLeagueList = self::field('user_id,league_id')
                ->where('league_id', 'in', $leagueIds)
                ->select();
            foreach ($authLeagueList as $k => $v) {
                $childrenAdminIds[] = $v['user_id'];
            }
        }
        if ($withself) {
            if (!in_array($admin_id, $childrenAdminIds)) {
                $childrenAdminIds[] = $admin_id;
            }
        } else {
            $childrenAdminIds = array_diff($childrenAdminIds, [$admin_id]);
        }
        return $childrenAdminIds;


    }

    /**
     * 判断是否是超级管理员
     * @return bool
     */
    public static function isSuperAdmin($admin_id)
    {
        $auth = new \app\admin\library\Auth();
        return in_array('*', $auth->getRuleIds($admin_id)) ? true : false;
    }

    /**
     * 取出当前负责人管理的下级部门
     * @param boolean $withself 是否包含当前所在的分组
     * @return array
     */
    public static function getChildrenLeagueIds($admin_id, $withself = false)
    {
        //取出当前负责人所有部门
        if (self::isSuperAdmin($admin_id)) {
            $leagues = LeagueModel::allLeague();
        } else {
            $leagues = self::getLeagues($admin_id, 1);
        }

        $departmenIds = [];
        foreach ($leagues as $k => $v) {
            $departmenIds[] = $v['id'];
        }
        $originDepartmenId = $departmenIds;
        foreach ($leagues as $k => $v) {
            if (in_array($v['parent_id'], $originDepartmenId)) {
                $departmenIds = array_diff($departmenIds, [$v['id']]);
                unset($leagues[$k]);
            }
        }
        // 取出所有部门
        $leagueList = \app\admin\model\league\League::allLeague();
        $objList = [];
        foreach ($leagues as $k => $v) {
            // 取出包含自己的所有子节点
            $childrenList = Tree::instance()->init($leagueList, 'parent_id')->getChildren($v['id'], true);
            $obj = Tree::instance()->init($childrenList, 'parent_id')->getTreeArray($v['parent_id']);
            $objList = array_merge($objList, Tree::instance()->getTreeList($obj));
        }
        $childrenDepartmenIds = [];
        foreach ($objList as $k => $v) {
            $childrenDepartmenIds[] = $v['id'];
        }
        if (!$withself) {
            $childrenDepartmenIds = array_diff($childrenDepartmenIds, $departmenIds);
        }
        return $childrenDepartmenIds;
    }


    /**
     * 根据用户id获取所在部门,返回值为数组
     * @param int $admin_id admin_id
     * @param int $admin_id $is_principal 是否只取负责的部分
     * @return array       用户所属的部门 array(
     *                  array('admin_id'=>'员工id','league_id'=>'部门id','name'=>'部门名称'),
     *                  ...)
     */
    public static function getLeagues($admin_id, $is_principal = 0)
    {
        static $leagues = [];
        if (isset($leagues[$admin_id])) {
            return $leagues[$admin_id];
        }

        // 执行查询
        $user_leagues = Db::name('league_admin')
            ->alias('da')
            ->join('__' . strtoupper('league') . '__ d', 'da.league_id = d.id', 'LEFT')
            ->field('da.user_id,da.league_id,d.id,d.parent_id,d.name')
            ->where("da.user_id='{$admin_id}' " . ($is_principal ? "and is_principal=1" : '') . " and d.status='normal'")
            ->fetchSql(false)
            ->select();
        $leagues[$admin_id] = $user_leagues ?: [];
        return $leagues[$admin_id];
    }

    /**
     * 获取当前用户可管理的所有部门
     * @param $admin_id
     * @param bool $isSuperAdmin
     * @return array|bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getAllLeagues($admin_id, $isSuperAdmin = false)
    {

        if ($isSuperAdmin) {
            $leagueList = LeagueModel::allLeague();
        } else {
            $leagueIds = \app\admin\model\league\Admin::getChildrenLeagueIds($admin_id, true);
            $leagueList = collection(LeagueModel::where('id', 'in', $leagueIds)->select())->toArray();
        }
        return $leagueList;
    }

    /**
     * 获取当前用户可管理的所有部门[key=>value]
     * @param $admin_id
     * @param bool $isSuperAdmin
     * @return array|bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getAllLeaguesTreeArray($admin_id, $isSuperAdmin = false)
    {

        $leaguedata = array();
        if ($isSuperAdmin) {
            $leagueList = LeagueModel::allLeague();
            Tree::instance()->init($leagueList, 'parent_id');
            $result = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0));
            foreach ($result as $k => $v) {
                $leaguedata[$v['id']] = $v['name'];
            }
        } else {
            //获取当前可管理部门
            $leagueIds = \app\admin\model\league\Admin::getChildrenLeagueIds($admin_id, true);
            $leagueList = collection(LeagueModel::where('id', 'in', $leagueIds)->select())->toArray();
            Tree::instance()->init($leagueList, 'parent_id');

            $leagues = \app\admin\model\league\Admin::getLeagues($admin_id);
            $issetIDs = array_column($leagues, 'id');
            foreach ($leagues as $m => $n) {
                if ($n['parent_id'] == 0) {
                    $result1 = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0));
                    foreach ($result1 as $k => $v) {
                        $leaguedata[$v['id']] = $v['name'];
                    }
                } else {
                    if (in_array($n['parent_id'], $issetIDs)) continue;
                    $childlist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(($n['parent_id'])));
                    foreach ($childlist as $k => $v) {
                        $leaguedata[$v['id']] = $v['name'];
                    }
                }
            }
        }

        return $leaguedata;
    }

    /**
     * 获取当前用户可管理的所有部门
     * @param $admin_id
     * @param bool $isSuperAdmin
     * @return array|bool|false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getAllLeaguesArray($admin_id, $isSuperAdmin = false)
    {
        $leagueList = array();
        if ($isSuperAdmin) {
            $leagueList = LeagueModel::allLeague();
            Tree::instance()->init($leagueList, 'parent_id');
            $leagueList = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0));

        } else {
            //获取当前可管理部门
            $leagueIds = self::getChildrenLeagueIds($admin_id, true);

            $dList = collection(LeagueModel::where('id', 'in', $leagueIds)->select())->toArray();
            Tree::instance()->init($dList, 'parent_id');


            $leagues = \app\admin\model\league\Admin::getLeagues($admin_id);
            $issetIDs = array_column($leagues, 'id');

            foreach ($leagues as $m => $n) {
                if ($n['parent_id'] != 0) {
                    if (in_array($n['parent_id'], $issetIDs)) continue;
                    $childlist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(($n['parent_id'])));
                    foreach ($childlist as $k => $v) {
                        $k == 0 ? $v['parent_id'] = 0 : '';
                        $leagueList[] = $v;
                    }
                } else {
                    $childlist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray($n['id']));
                    $childlist ? $n['haschild'] = 1 : '';
                    $leagueList[] = $n;
                    foreach ($childlist as $k => $v) {
                        $leagueList[] = $v;
                    }
                }
            }
        }
        return $leagueList;

    }


    /**
     * 获取上级负责人
     * @param bool $parent 如果当前部门没负责人，是否逐级寻找？
     * @param array $ignore 是否忽略当前uid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getParentAdminIds($uid, $parent = true,$ignore=false)
    {
        $principalIds = [];
        $leagueIds = self::getLeagueIds($uid);//获取当前用户的所有部门ID，
        if ($leagueIds) {
            $principalIds = self::getDprincipalIds($leagueIds, $parent,$ignore?[$uid]:[]);
        }
        return $principalIds;
    }


    /**
     * 获取部门的负责人
     * @param $leagueIds 部门IDs
     * @param bool $parent 如果当前部门没负责人，是否逐级寻找？
     * @param array $ignore_ids 忽略的adminids
     * @return array|bool|string
     */
    public static function getDprincipalIds($leagueIds, $parent = true,$ignore_ids=[])
    {
        $daModel=Db::name('league_admin');
        if ($ignore_ids){
            $daModel->where('da.admin_id', 'not in', $ignore_ids);
        }
        //获取当前部门负责人
        $principalIds =$daModel
            ->alias('da')
            ->join('__' . strtoupper('league') . '__ d', 'da.league_id = d.id')
            ->where('da.league_id', 'in', $leagueIds)
            ->where('is_principal', 1)
            ->where('d.status', 'normal')
            ->column('da.user_id');
        if ($principalIds) {
            return $principalIds;//如果存在就直接返回
        }
        //上一级查找
        foreach ($leagueIds as $k => $v) {
            $newLeagueIds = League::getParentId($v);

            if ($newLeagueIds) {
                return self::getDprincipalIds($newLeagueIds, $parent);
            }
        }
        return [];

    }

}
