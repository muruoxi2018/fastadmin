<?php

namespace app\admin\model\user;

use think\Model;


class Grading extends Model
{

    

    

    // 表名
    protected $name = 'user_grading';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'state_text'
    ];
    

    
    public function getStateList()
    {
        return ['待审核' => __('待审核'), '审核通过' => __('审核通过'), '审核未通过' => __('审核未通过')];
    }


    public function getStateTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['state']) ? $data['state'] : '');
        $list = $this->getStateList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function category()
    {
        return $this->belongsTo('app\admin\model\Category', 'type', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function user()
    {
        return $this->belongsTo('app\admin\model\User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
