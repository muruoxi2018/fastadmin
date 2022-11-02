<?php

namespace app\api\controller\ygame;

use app\common\controller\Api;
use EasyWeChat\Factory;

/**
 * Common接口
 */
class Common extends Api
{
    // 无需登录的接口,*表示全部
    protected $noNeedLogin = [];
    // 无需鉴权的接口,*表示全部
    protected $noNeedRight = ['*'];

    /**
     * 获取jssign
     * @throws \think\Exception
     */
    public function jssign()
    {
        $url = $this->request->post('url');

        $config = get_addon_config('ygame');
        $options = array(
            'token'          => $config['token'], //填写你设定的key
            'aes_key' => $config['encodingaeskey'], //填写加密用的EncodingAESKey
            'app_id'          => $config['appid'], //填写高级调用功能的app id
            'secret'      => $config['appsecret'] //填写高级调用功能的密钥
        );

        $app = Factory::officialAccount($options);


        $app->jssdk->setUrl(htmlspecialchars_decode($url));
        $config = $app->jssdk->buildConfig(['updateAppMessageShareData','updateTimelineShareData','onMenuShareWeibo','onMenuShareQZone'], $debug = false, $beta = false, $json = true);

        $this->success('请求成功',$config);
    }

}
