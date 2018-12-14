<?php

namespace app\admin\model;

use think\Model;

class ProjectVoucher extends Model
{
    // 表名
    protected $name = 'project_voucher';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    
    // 追加属性
    protected $append = [
        'push_time_text',
        'edit_time_text'
    ];
    

    



    public function getPushTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['push_time']) ? $data['push_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getEditTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['edit_time']) ? $data['edit_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setPushTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }

    protected function setEditTimeAttr($value)
    {
        return $value && !is_numeric($value) ? strtotime($value) : $value;
    }


}
