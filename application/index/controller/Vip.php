<?php

namespace app\index\controller;

use addons\vip\library\Service;
use addons\vip\model\Order;
use addons\vip\model\Record;
use app\common\controller\Frontend;
use think\Exception;

/**
 * 购买VIP
 */
class Vip extends Frontend
{
    protected $layout = 'default';
    protected $noNeedLogin = ['pay', 'epay'];
    protected $noNeedRight = ['*'];

    /**
     * VIP列表
     * @return string
     */
    public function viplist()
    {
        $config = get_addon_config('vip');
        $vipList = [];
        $vipList = \addons\vip\model\Vip::where('status', '=', 'normal')->field('sales', true)->order('level', 'asc')->select();

        $paytypeList = [];
        foreach (explode(',', $config['paytypelist']) as $index => $item) {
            $paytypeList[] = ['value' => $item, 'image' => '/assets/addons/vip/img/' . $item . '.png', 'default' => $item === $config['defaultpaytype']];
        }
        $vipInfo = Service::getVipInfo();

        $this->view->assign('addonConfig', $config);
        $this->view->assign('vipList', $vipList);
        $this->view->assign('vipInfo', $vipInfo);
        $this->view->assign('paytypeList', $paytypeList);
        $this->view->assign('title', __('VIP列表'));
        return $this->view->fetch();
    }

    /**
     * VIP日志
     * @return string
     */
    public function record()
    {
        $recordList = Record::with(['vip'])->where('user_id', $this->auth->id)
            ->where('status', '<>', 'created')
            ->order('id', 'desc')
            ->paginate();

        $vipInfo = Service::getVipInfo();
        $this->view->assign('title', "VIP日志");
        $this->view->assign('recordList', $recordList);
        $this->view->assign('vipInfo', $vipInfo);
        return $this->view->fetch();
    }

    /**
     * 创建订单并发起支付请求
     */
    public function submit()
    {
        $level = $this->request->param('level/d');
        $days = $this->request->param('days/d');
        $paytype = $this->request->param('paytype', '');

        $vipInfo = \addons\vip\model\Vip::getByLevel($level);
        if (!$vipInfo) {
            $this->error('未找到VIP相关信息');
        }
        if ($this->auth->vip > $vipInfo['level']) {
            $this->error('当前VIP等级已高于购买的VIP等级');
        }

        $lastRecordInfo = Record::getLastRecord();

        $recordInfo = Record::where('user_id', $this->auth->id)
            ->where('status', 'created')
            ->where('level', $level)->where('days', $days)
            ->whereTime('createtime', '-30 minutes')
            ->find();
        if (!$recordInfo) {
            $amount = $vipInfo->getPriceByDays($days);
            $insert = [
                'user_id' => $this->auth->id,
                'vip_id'  => $vipInfo->id,
                'level'   => $vipInfo->level,
                'days'    => $days,
                'amount'  => $amount,
                'status'  => 'created',
            ];
            $recordInfo = Record::create($insert);
        }

        try {
            $response = \addons\vip\library\Order::submit($vipInfo->id, $recordInfo->id, $recordInfo->amount, $paytype);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }

        return $response;
    }

    /**
     * 企业支付通知和回调
     */
    public function epay()
    {
        $type = $this->request->param('type');
        $paytype = $this->request->param('paytype');
        if ($type == 'notify') {
            $pay = \addons\epay\library\Service::checkNotify($paytype);
            if (!$pay) {
                echo '签名错误';
                return;
            }
            $data = $pay->verify();
            try {
                $payamount = $paytype == 'alipay' ? $data['total_amount'] : $data['total_fee'] / 100;
                \addons\vip\library\Order::settle($data['out_trade_no'], $payamount);
            } catch (Exception $e) {
            }
            echo $pay->success();
        } else {
            $pay = \addons\epay\library\Service::checkReturn($paytype);
            if (!$pay) {
                $this->error('签名错误');
            }
            //微信支付没有返回链接
            if ($pay === true) {
                $this->success("请返回网站查看支付状态!", "index/vip/viplist");
            }

            //你可以在这里定义你的提示信息,但切记不可在此编写逻辑
            $this->success("恭喜你！支付成功!", url("index/vip/viplist"));
        }
        return;
    }
}
