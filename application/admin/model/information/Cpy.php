<?php

namespace app\admin\model\information;

use think\Model;


class Cpy extends Model
{

    

    

    // 表名
    protected $name = 'information_cpy';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];
    

    







    public function user()
    {
        return $this->belongsTo('app\admin\model\User', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }


    public function category()
    {
        return $this->belongsTo('app\admin\model\Category', 'category_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
