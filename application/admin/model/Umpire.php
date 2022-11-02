<?php

namespace app\admin\model;

use think\Model;


class Umpire extends Model
{

    

    

    // 表名
    protected $name = 'umpire';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'identity_text'
    ];
    

    
    public function getIdentityList()
    {
        return ['裁判长' => __('裁判长'), '编排长' => __('编排长'), '裁判员' => __('裁判员')];
    }


    public function getIdentityTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['identity']) ? $data['identity'] : '');
        $list = $this->getIdentityList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function achievement()
    {
        return $this->belongsTo('Achievement', 'achievement_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
