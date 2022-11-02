<?php

namespace app\admin\model\league\signin;

use think\Model;


class Info extends Model
{

    

    

    // 表名
    protected $name = 'league_signin_info';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'channel_text'
    ];
    

    
    public function getChannelList()
    {
        return ['Web' => __('Web'), 'WeChat' => __('Wechat'), 'manual' => __('Manual')];
    }


    public function getChannelTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['channel']) ? $data['channel'] : '');
        $list = $this->getChannelList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function signin()
    {
        return $this->belongsTo('app\admin\model\league\Signin', 'league_signin_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function user()
    {
        return $this->belongsTo('app\admin\model\User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
