<?php

namespace app\api\controller\ygame;

use app\common\controller\Api;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;


/**
 * 成员列表
 */
class Person extends Api
{
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = [];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 获取成员列表
     * @throws \think\Exception
     */
    public function index()
    {
        $page = $this->request->post('page');
        $limit = $this->request->post('limit');

        $projectModel = new \addons\ygame\service\Person();
        $data = $projectModel->getPersonList(['user_id'=>$this->auth->id],$page,$limit);

        $this->success('请求成功', $data);
    }

    /**
     * 添加团队成员
     */
    public function add(){
        $person_name = $this->request->post('person_name');
        $mobile = $this->request->post('mobile');
        $idcard = $this->request->post('idcard');

        $data = ['person_name'=>$person_name,'mobile'=>$mobile,'idcard'=>$idcard,'user_id'=>$this->auth->id];
        $project = new \addons\ygame\service\Person();
        if($project->addPerson($data)){
            $this->success("请求成功");
        }else{
            $this->error($project->error);
        }

    }

    /**
     * 编辑成员
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function edit(){
        $id = $this->request->post('id');
        $person_name = $this->request->post('person_name');
        $mobile = $this->request->post('mobile');
        $idcard = $this->request->post('idcard');

        $data = ['person_name'=>$person_name,'mobile'=>$mobile,'idcard'=>$idcard,'user_id'=>$this->auth->id];
        $project = new \addons\ygame\service\Person();
        if($project->editPerson($id,$data)){
            $this->success("请求成功");
        }else{
            $this->error($project->error);
        }
    }


    /**
     * 获取人员详情
     */
    public function info(){
        $id = $this->request->post('id');
        $project = new \addons\ygame\service\Person();
        try {
            if ($info = $project->getPersonInfo($id)) {
                $this->success("请求成功", $info);
            } else {
                $this->error($project->error);
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }


    /**
     * 删除成员
     */
    public function del(){
        $id = $this->request->post('id');
        $project = new \addons\ygame\service\Person();
        try {
            if ($project->delPerson($id, $this->auth->id)) {
                $this->success("请求成功");
            } else {
                $this->error($project->error);
            }
        } catch (Exception $e) {
            $this->error($project->error);
        }
    }


}
