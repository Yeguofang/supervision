<?php

namespace app\admin\model;

use think\Model;

class Project extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'create_time';

    protected static function init()
    {
        self::beforeInsert(function ($row) {
            $row['create_time'] = time();
        });

    }
}
