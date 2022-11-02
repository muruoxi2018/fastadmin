<?php

namespace app\api\controller\ygame;

use app\common\controller\Api;

/**
 * 文章接口
 */
class Article extends Api
{
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = [];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 获取文章列表
     * @throws \think\Exception
     */
    public function index()
    {
        $project_id = $this->request->post('project_id');
        $page = $this->request->post('page');
        $limit = $this->request->post('limit');

        $where = ['project_id'=>$project_id];

        $service = new \addons\ygame\service\Article();
        $data = $service->getArticleList($where,$page,$limit);

        $this->success('请求成功', $data);
    }


    /**
     * 获取文章详情
     * @throws \think\Exception
     */
    public function info()
    {
        $id = $this->request->post('id');

        $service = new \addons\ygame\service\Article();
        $data = $service->getArticleInfo($id);
        $data['content']=preg_replace('#src="/#is', 'src="'.$this->request->domain().'/',$data['content']);
        $this->success('请求成功', $data);
    }

}
