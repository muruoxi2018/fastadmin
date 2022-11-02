<?php

namespace app\api\controller\ygame;

use app\common\controller\Api;


/**
 * 组别列表
 */
class Group extends Api
{

    // 无需登录的接口,*表示全部
    protected $noNeedLogin = [];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 获取组别列表
     * @throws \think\Exception
     */
    public function index()
    {
        $project_id = $this->request->post('project_id');
        $where = [
            'project_id'=>$project_id,
        ];

        $projectModel = new \addons\ygame\service\Group();
        $data = $projectModel->getGroupList($where);

        $this->success('请求成功', $data);
    }


}
