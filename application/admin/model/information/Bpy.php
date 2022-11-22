<?php

namespace app\admin\model\information;

use think\Model;


class Bpy extends Model
{

    

    

    // 表名
    protected $name = 'information_bpy';
    
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


    public function project()
    {
        return $this->belongsTo('app\admin\model\ygame\Project', 'ygame_project_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
