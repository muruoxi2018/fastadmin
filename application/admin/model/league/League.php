<?php

namespace app\admin\model\league;

use app\admin\model\AuthGroup;
use fast\Tree;
use think\Cache;
use think\Db;
use think\Exception;
use think\Model;

class League extends Model
{
    // 表名
    protected $name = 'league';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    protected static $config = [];


    /**
     *  缓存key
     * @var string
     */
    protected static $cachekey = "AllLeagueCachekey";


    protected static function init()
    {
        $config = static::$config = get_addon_config('league');

        self::beforeUpdate(function ($row) {
            if (isset($row['parent_id'])) {
                $childrenIds = self::getChildrenIds($row['id'], true);
                if (in_array($row['parent_id'], $childrenIds)) {
                    throw new Exception("上级组织部门不能是其自身或子组织部门");
                }
            }
        });
        self::afterInsert(function ($row) {
            //创建时自动添加权重值
            $pk = $row->getPk();
            $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
            self::clearCache();
        });
        self::afterDelete(function ($row) {
            //删除时，删除子节点，同时将所有相关部门成员删除
            $childIds = self::getChildrenIds($row['id']);
            if ($childIds) {
                League::destroy(function ($query) use ($childIds) {
                    $query->where('id', 'in', $childIds);
                });
            }
            $childIds[] = $row['id'];
            db('league_admin')->where('league_id', 'in', $childIds)->delete();
            self::clearCache();
        });
        self::afterWrite(function ($row) use ($config) {
            $changed = $row->getChangedData();
            //隐藏时判断是否有子节点,有则隐藏
            if (isset($changed['status']) && $changed['status'] == 'hidden') {
                $childIds = self::getChildrenIds($row['id']);
                db('league')->where('id', 'in', $childIds)->update(['status' => 'hidden']);
            }
            self::clearCache();

        });
    }

    public static function getStatusList()
    {
        return ['normal' => __('Normal'), 'hidden' => __('Hidden')];
    }


    /**
     * 获取栏目的所有子节点ID
     * @param int $id 栏目ID
     * @param bool $withself 是否包含自身
     * @return array
     */
    public static function getChildrenIds($id, $withself = false)
    {
        static $tree;
        if (!$tree) {
            $tree = \fast\Tree::instance();
            $tree->init(collection(self::order('weigh desc,id desc')->field('id,parent_id,name,status')->cache(self::$cachekey)->select())->toArray(), 'parent_id');
        }
        $childIds = $tree->getChildrenIds($id, $withself);
        return $childIds;
    }

    /**
     * 所有部门
     */
    public static function allLeague()
    {
        return self::order('weigh desc,id desc')->where(['status' => 'normal'])->cache(self::$cachekey)->select();
    }

    /**
     * 清空缓存
     * @param $name
     */
    public static function clearCache()
    {
        Cache::rm(self::$cachekey);
    }


    /**
     * 获取权限组
     * @param array $childrenGroupIds
     * @param null $groups 如果不是超级管理员传auth->getGroups(),的值
     * @return array
     */
    public static function getGroupdata($childrenGroupIds = [], $groups = null)
    {
        $groupList = collection(AuthGroup::where('id', 'in', $childrenGroupIds)->select())->toArray();
        Tree::instance()->init($groupList, 'pid');
        $groupdata = [];
        if ($groups) {
            $result = [];
            foreach ($groups as $m => $n) {
                $childlist = Tree::instance()->getTreeList(Tree::instance()->getTreeArray($n['id']));
                $temp = [];
                foreach ($childlist as $k => $v) {
                    $temp[$v['id']] = $v['name'];
                }
                $result[__($n['name'])] = $temp;
            }
            $groupdata = $result;

        } else {

            $result = Tree::instance()->getTreeList(Tree::instance()->getTreeArray(0));
            foreach ($result as $k => $v) {
                $groupdata[$v['id']] = $v['name'];
            }
        }
        return $groupdata;
    }

    /**
     * 获取当前部门归属的最高组织id(最顶级)
     */
    public static function getOrganiseID($leagueid)
    {
        if (!$leagueid) return 0;
        $organise_id = self::where(['id' => $leagueid])->value('organise_id');
        return $organise_id ? $organise_id : $leagueid;//如果没有最高，本身自己就是最高；

    }

    /**
     * 获取上级id
     */
    public static function getParentId($id, $withself = false){
        static $tree;
        if (!$tree) {
            $tree = \fast\Tree::instance();
            $tree->init(collection(self::allLeague())->toArray(), 'parent_id');
        }
        $parent = $tree->getParent($id, $withself);

        return array_column($parent,'id');
    }

    /**
     * 获取所有上级ids
     */
    public static function getParentIds($id, $withself = false){
        static $tree;
        if (!$tree) {
            $tree = \fast\Tree::instance();
            $tree->init(collection(self::allLeague())->toArray(), 'parent_id');
        }
        $parents = $tree->getParents($id, $withself);
        return array_column($parents,'id');
    }









}