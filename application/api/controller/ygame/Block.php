<?php

namespace app\api\controller\ygame;

use app\common\controller\Api;
use think\Exception;

/**
 * 其他接口
 */
class Block extends Api
{
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['notifyx'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 支付成功回调
     */
    public function notifyx()
    {
        $paytype = "wechat";
        $pay = \addons\epay\library\Service::checkNotify($paytype);
        if (!$pay) {
            echo '签名错误';
            return;
        }
        $data = $pay->verify();
        try {

            $out_trade_no = explode("_",$data['out_trade_no']);
            if(empty($out_trade_no[0])){
                return;
            }
            $order_no = $out_trade_no[0];
            $orderModel = new \app\admin\model\ygame\Order();
            if($orderInfo = $orderModel->where(['order_no'=>$order_no,'status'=>1])->find()){
                $orderInfo->save(['order_no'=>$order_no,'status'=>2,'pay_status'=>1,'pay_time'=>time()]);
                $recordModel = new \app\admin\model\ygame\Record();
                $recordModel->save(['status'=>1],['order_id'=>$orderInfo['id']]);
            }


        } catch (Exception $e) {

        }
        echo $pay->success();
    }

}
