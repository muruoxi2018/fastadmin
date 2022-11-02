<?php

namespace app\admin\model\ygame;

use think\Model;


class Team extends Model
{

    // 表名
    protected $name = 'ygame_team';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = "int";

    // 定义时间戳字段名
    protected $createTime = "createtime";
    protected $updateTime = "updatetime";
    protected $deleteTime = false;

}
