<?php

namespace app\admin\model;

use think\Model;


class Rival extends Model
{

    

    

    // 表名
    protected $name = 'rival';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'result_text'
    ];
    

    
    public function getResultList()
    {
        return ['胜' => __('胜'), '负' => __('负'), '平' => __('平'), '双弃' => __('双弃'), '先手弃' => __('先手弃'), '后手弃' => __('后手弃')];
    }


    public function getResultTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['result']) ? $data['result'] : '');
        $list = $this->getResultList();
        return isset($list[$value]) ? $list[$value] : '';
    }




    public function achievement()
    {
        return $this->belongsTo('Achievement', 'achievement_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
