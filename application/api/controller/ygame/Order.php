<?php

namespace app\api\controller\ygame;

use app\admin\model\ygame\Wechat;
use app\common\controller\Api;
use Yansongda\Pay\Exceptions\GatewayException;

/**
 * 订单接口
 */
class Order extends Api
{
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = [];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 获取订单列表
     * @throws \think\Exception
     */
    public function index()
    {
        $status = $this->request->post('status',0);
        $page = $this->request->post('page');
        $limit = $this->request->post('limit');

        $order = new \addons\ygame\service\Order();
        $data = $order->getOrderList($status,['user_id'=>$this->auth->id],$page,$limit);
        foreach($data['data'] as &$v){
            $v['project_image'] = $this->request->domain().$v['project_image'];
        }

        $this->success('请求成功', $data);
    }

    /**
     * 取消订单
     */
    public function cancel(){
        $order_id = $this->request->post("order_id");
        $order = new \addons\ygame\service\Order();
        if($order->cancelOrder($order_id,$this->auth->id)){
            $this->success('请求成功');
        }else{
            $this->error($order->error);
        }

    }

    /**
     * 获取订单数量
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function num(){
        $status = $this->request->post("status");
        $order = new \addons\ygame\service\Order();

        $data = $order->getOrderNum($status,$this->auth->id);
        $this->success('请求成功', $data);
    }

    /**
     * 支付订单
     */
    public function pay(){
        $order_id = $this->request->post("order_id");
        $from = $this->request->post("from",'mp');
        $order = new \addons\ygame\service\Order();
        if($orderInfo = $order->getOrderInfo($order_id,$this->auth->id)){

            if($orderInfo['status'] != 1){
                $this->error('当前订单已支付');
            }

            $out_trade_id = $orderInfo['order_no']."_".rand(1000,9999);
            $orderInfo->save(['out_trade_id'=>$out_trade_id]);

            $wechatUser = new Wechat();
            $wechatUserInfo = $wechatUser->where(['user_id'=>$this->auth->id])->find();
            $project = new \addons\ygame\service\Project();

            $projectInfo = $project->getProjectInfo(['id'=>$orderInfo['project_id']])->find();


            $params = [
                'amount'=>$orderInfo['price'],
                'orderid'=>$out_trade_id,
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
            $this->error('当前订单不存在');
        }
    }
}
