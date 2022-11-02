<?php

namespace app\admin\controller\league;

use app\common\controller\Backend;
use \app\admin\model\league\League as LeagueModel;
use fast\Tree;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;


/**
 * 部门管理
 */
class Index extends Backend
{

    protected $tree = null;
    protected $leagueList;
    protected $noNeedRight=['selectpage'];

    public function _initialize()
    {

        parent::_initialize();
        $this->model = new LeagueModel;

        $this->tree = Tree::instance();
        $this->tree->init(LeagueModel::allLeague(), 'parent_id');
        $this->leagueList = $this->tree->getTreeList($this->tree->getTreeArray(0), 'name');
        $this->view->assign("leagueList", $this->leagueList);

        $this->view->assign("statusList", LeagueModel::getStatusList());
    }

    /**
     * 部门列表
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            $searchValue = $this->request->request("searchValue");
            $search = $this->request->request("search");

            //构造父类select列表选项数据
            $list = [];
            if ($search||$searchValue) {

                foreach ($this->leagueList as $k => &$v) {

                    if ($search&&stripos($v['name'], $search) !== false) {
                        $list[] = $v;
                    }
                    if ($searchValue&&in_array($v['id'], explode(',',$searchValue)) !== false) {
                        $v['name']=preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/", " ", strip_tags($v['name'])); //过滤空格
                        $list[] = $v;
                    }
                }
            } else {
                $list = $this->leagueList;
            }

            $list = array_values($list);


            foreach ($list as $k => &$v) {
                $v['pid'] = $v['parent_id'];
            }
            $total = count($list);
            $result = array("total" => $total, "rows" => $list);

            return json($result);

        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
                    $params[$this->dataLimitField] = $this->auth->id;
                }
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : true) : $this->modelValidate;
                        $this->model->validate($validate);
                    }
                    $nameArr = array_filter(explode("\n", str_replace("\r\n", "\n", $params['name'])));

                    //获取组织最高的ID；
                    $params['organise_id'] = LeagueModel::getOrganiseID(isset($params['parent_id']) ? $params['parent_id'] : 0);

                    if (count($nameArr) > 1) {
                        foreach ($nameArr as $index => $item) {
                            $params['name'] = $item;
                            $result = $this->model->allowField(true)->isUpdate(false)->data($params)->save();
                        }
                    } else {
                        $result = $this->model->allowField(true)->save($params);
                    }
                    if ($result !== false) {
                        //清空部门缓存
                        LeagueModel::clearCache();
                        $this->success();
                    } else {
                        $this->error($this->model->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        return $this->view->fetch();
    }

    public function edit($ids = null)
    {
        $row = LeagueModel::get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    //最顶级的不能修改成下级
                    if ($row['parent_id']==0&&$params['parent_id']){
                        \exception(__("Top-level organization cannot be modified to lower level organization"));
                    }

                    //获取修改后的组织最高的ID；
                    //获取组织最高的ID；
                    $organise_id = isset($params['parent_id']) ? LeagueModel::getOrganiseID($params['parent_id']) : 0;
                    //判断修改的父类是否和之前的一样并且organise_id不一样就要修改,含子类
                    if ($params['parent_id'] != $row['parent_id'] && $organise_id != $row['organise_id']) {
                        $leagueIds = LeagueModel::getChildrenIds($row->id, true);
                        $mapwhere['id'] = ['in', $leagueIds];
                        $leagueIds = LeagueModel::update(['organise_id' => $organise_id], $mapwhere);
                    }
                    $result = $row->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    //清空部门缓存
                    LeagueModel::clearCache();
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        } else {

            $row = $row->toArray();
            $childrenIds = $this->tree->getChildrenIds($row['id'], true);
            $this->view->assign('childrenIds', $childrenIds);
            $this->view->assign("row", $row);
            return $this->view->fetch();
        }


    }


    public function selectpage(){
       return $this->index();
    }
}
