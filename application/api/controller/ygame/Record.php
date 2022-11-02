<?php

namespace app\api\controller\ygame;


use app\admin\model\ygame\Wechat;
use app\common\controller\Api;
use think\Exception;
use Yansongda\Pay\Exceptions\GatewayException;


/**
 * 报名查询接口
 */
class Record extends Api
{

    // 无需登录的接口,*表示全部
    protected $noNeedLogin = [];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];


    public function search(){
        $name = $this->request->post('name');
        $idcard = $this->request->post('idcard');

        $record = new \addons\ygame\service\Record();
        $data = $record->scanRecordList($name,$idcard);
        $this->success("请求成功",$data);
    }

    /**
     * 获取报名信息
     */
    public function members(){
        $order_id = $this->request->post('order_id');
        $record = new \addons\ygame\service\Record();
        $data = $record->getRecordMembers($order_id,$this->auth->id);
        $this->success("请求成功",$data);
    }

}
