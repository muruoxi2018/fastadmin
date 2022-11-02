<?php

namespace app\admin\model\ygame;

use think\Model;


class Record extends Model
{

    

    

    // 表名
    protected $name = 'ygame_record';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'refund_time_text'
    ];
    

    



    public function getRefundTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['refund_time']) ? $data['refund_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setRefundTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
