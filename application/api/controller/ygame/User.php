<?php

namespace app\api\controller\ygame;

use addons\ygame\library\Service;
use app\common\controller\Api;
use EasyWeChat\Factory;
use think\Exception;

/**
 * 用户接口
 */
class User extends Api
{

    // 无需登录的接口,*表示全部
    protected $noNeedLogin = ['appid','authUser'];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     *获取APPID
     */
    public function appid(){
        $config = get_addon_config('ygame');
        $appid = $config['appid'];
        
        $this->success('请求成功',['appid'=>$appid]);
    }

    /**
     * 微信h5授权登录
     */
    public function authUser(){

        $config = get_addon_config('ygame');
        $options = array(
            'token'          => $config['token'], //填写你设定的key
            'aes_key' => $config['encodingaeskey'], //填写加密用的EncodingAESKey
            'app_id'          => $config['appid'], //填写高级调用功能的app id
            'secret'      => $config['appsecret'] //填写高级调用功能的密钥
        );

        $app = Factory::officialAccount($options);
        $oauth = $app->oauth;

        $user = $oauth->user();
        if($user){
            $loginret = Service::connect(['openid'=>$user['original']['openid'],'headimgurl'=>$user['avatar'],'nickname'=>$user['nickname']]);

            if ($loginret) {
                $_userinfo = $this->auth->getUserinfo();

                $data = [
                    'user_id'=> $_userinfo['id'],
                    'avatarUrl'=> $user['avatar'],
                    'nickName'=> $user['nickname'],
                    'token'  => $_userinfo['token']
                ];

                $this->success(__('登录成功'), $data);
            }
            $this->error('请求失败');
        }
    }

}
