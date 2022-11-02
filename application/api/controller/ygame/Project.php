<?php

namespace app\api\controller\ygame;


use app\admin\model\ygame\Wechat;
use app\common\controller\Api;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\exception\DbException;
use Yansongda\Pay\Exceptions\GatewayException;


/**
 * 赛事接口
 */
class Project extends Api
{

    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['index'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];


    /**
     * 获取赛事列表
     * @throws \think\Exception
     */
    public function index()
    {
        $keyword = $this->request->post('keyword');
        $page = $this->request->post('page');
        $limit = $this->request->post('limit');

        $where = ['status'=>1];
        if($keyword){
            $where['project_name'] = ['like','%'.$keyword.'%'];
        }

        $projectModel = new \addons\ygame\service\Project();
        $data = $projectModel->getProjectList($where,$page,$limit);

        foreach($data['data'] as &$v){
            $v['image'] = $this->request->domain()."/".$v['image'];
        }
        $this->success('请求成功', $data);
    }

    /**
     * 获取赛事详情
     * @throws Exception
     */
    public function info(){
        $id = $this->request->post('id');
        $project = new \addons\ygame\service\Project();
        $info = $project->getProjectInfo(['status'=>1,'id'=>$id]);
        if($info){
            $info['image'] = $this->request->domain().$info['image'];

            //获取组别
            $info['groupNames'] = $project->getGroupName($info['id']);

            //获取组别
            $info['allowTeam'] = $project->getAllowTeam($info['id'])?1:0;

            //获取参与人数和头像
            $info['orderNum'] = $project->getRecordMum($info['id']);
            $info['orderMembers'] = $project->getOrderMember($info['id'],10);

            $info['content']=preg_replace('#src="/#is', 'src="'.$this->request->domain().'/',$info['content']);
            
            $this->success('请求成功', $info);
        }else{
            $this->success('请求失败');
        }
    }

    /**
     * 个人报名
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function submit(){
        $from = $this->request->post('from','mp');
        $project_id = $this->request->post('project_id');
        $group_id = $this->request->post('group_id');
        $name = $this->request->post('name');
        $mobile = $this->request->post('mobile');
        $idcard = $this->request->post('idcard');

        $project = new \addons\ygame\service\Project();
        if(!$projectInfo = $project->getProjectInfo(['id'=>$project_id,'status'=>1])){
            $this->error('当前赛事不存在');
        }

        $groupModel = new \addons\ygame\service\Group();
        if(!$groupInfo = $groupModel->getGroupInfo(['id'=>$group_id,'project_id'=>$project_id])){
            $this->error('当前组别不存在');
        }

        $data['type'] = 1;
        $data['project_id'] = $project_id;
        $data['group_id'] = $group_id;
        $data['price'] = $groupInfo['price'];
        $data['name'] = $name;
        $data['mobile'] = $mobile;
        $data['idcard'] = $idcard;
        $data['order_no'] = date("YmdHis").rand(10000,99999);
        $data['user_id'] = $this->auth->id;
        $data['price'] = $groupInfo['price'];

        $wechatUser = new Wechat();
        $wechatUserInfo = $wechatUser->where(['user_id'=>$this->auth->id])->find();
        if(empty($wechatUserInfo['openid'])){
            $this->error('当前用户信息有误，请稍候再试');
        }
        if($project->submit($data)){
            $params = [
                'amount'=>$groupInfo['price'],
                'orderid'=>$data['order_no'],
                'type'=>"wechat",
                'title'=>$projectInfo['project_name'],
                'notifyurl'=>$this->request->domain()."/api/ygame/block/notifyx",
                'returnurl'=>"",
                'method'=>$from,
                'openid'=>$wechatUserInfo['openid'],
            ];

            try {
                $result = \addons\epay\library\Service::submitOrder($params);
            }catch (GatewayException $e){
                $this->error($e->getMessage());
            }

            $this->success('请求成功',$result);
        }else{
            $this->error($project->error);
        }
    }


    /**
     * 团队报名
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function team_submit(){
        $from = $this->request->post('from','mp');
        $project_id = $this->request->post('project_id');
        $team_name = $this->request->post('team_name');
        $team_leader = $this->request->post('team_leader');
        $team_mobile = $this->request->post('team_mobile');
        $group = $this->request->post('group/a');

        $project = new \addons\ygame\service\Project();
        if(!$projectInfo = $project->getProjectInfo(['id'=>$project_id,'status'=>1])){
            $this->error('当前赛事不存在');
        }

        $data['type'] = 2;
        $data['project_id'] = $project_id;
        $data['order_no'] = date("YmdHis").rand(10000,99999);
        $data['group'] = $group;
        $data['user_id'] = $this->auth->id;
        $data['team_name'] = $team_name;
        $data['team_leader'] = $team_leader;
        $data['team_mobile'] = $team_mobile;

        $wechatUser = new Wechat();
        $wechatUserInfo = $wechatUser->where(['user_id'=>$this->auth->id])->find();
        if(empty($wechatUserInfo['openid'])){
            $this->error('当前用户信息有误，请稍候再试');
        }

        if($order = $project->team_submit($data)){
            try {
                $params = [
                    'amount'=>$order['price'],
                    'orderid'=>$order['order_no'],
                    'type'=>"wechat",
                    'title'=>$projectInfo['project_name'],
                    'notifyurl'=>$this->request->domain()."/api/ygame/block/notifyx",
                    'returnurl'=>"",
                    'method'=>$from,
                    'openid'=>$wechatUserInfo['openid'],
                ];
                $result = \addons\epay\library\Service::submitOrder($params);
            }catch (GatewayException $e){
                $this->error($e->getMessage());
            }
            $this->success('报名成功',$result);
        }else{
            $this->error($project->error);
        }
    }

    /**
     * 团队报名成员
     */
    public function team_member(){
        $keyword = $this->request->post('keyword');
        $project_id = $this->request->post('project_id');
        $page = $this->request->post('page');
        $limit = $this->request->post('limit');

        $projectModel = new \addons\ygame\service\Person();
        $where = ['user_id'=>$this->auth->id];
        if($keyword){
            $where['person_name'] = ['like','%'.$keyword.'%'];
        }
        $data = $projectModel->getPersonList($where,$page,$limit);

        //获取当前赛事已经报名的成员ID
        $project = new \addons\ygame\service\Project();
        $idcards = $project->getTeamMemberIdcards($project_id,$page,$limit);

        foreach($data['data'] as &$v){
            if(in_array($v['idcard'],$idcards)){
                $v->disable = true;
            }else{
                $v->disable = false;
            }
        }

        $this->success('请求成功',$data);

    }

    /**
     * 获取团队基本信息
     */
    public function team(){
        $project_id = $this->request->post('project_id');
        $project = new \addons\ygame\service\Project();
        try {
            $teamInfo = $project->getTeamInfo($project_id,$this->auth->id);
            $this->success("请求成功",$teamInfo);
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

}
