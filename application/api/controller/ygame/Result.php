<?php

namespace app\api\controller\ygame;


use app\admin\model\ygame\Wechat;
use app\common\controller\Api;
use think\Exception;
use Yansongda\Pay\Exceptions\GatewayException;


/**
 * 成绩查询接口
 */
class Result extends Api
{

    // 无需登录的接口,*表示全部
    protected $noNeedLogin = [];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    public function search(){
        $name = $this->request->post('name');
        $idcard = $this->request->post('idcard');

        $record = new \addons\ygame\service\Result();
        $data = $record->scanResultList($name,$idcard);
        $this->success("请求成功",$data);
    }

    /**
     * 获取成绩证书
     */
    public function cert(){
        header('Content-Type: image/jpeg;');
        $result_id = $this->request->post("result_id");

        $result = new \addons\ygame\service\Result();
        if(!$filename = $result->general_cert($result_id)){
            $this->error($result->error);
        }else{
            $this->success('请求成功',$this->request->domain()."/".$filename);
        }




    }

}
