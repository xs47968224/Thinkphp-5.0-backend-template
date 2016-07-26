<?php
namespace app\admin\model;

use think\Model;

class Administrator extends Model
{

    // 设置完整的数据表（包含前缀）
    protected $table = 'think_administrator';

    // 关闭自动写入时间戳
    //protected $autoWriteTimestamp = false;

    //默认时间格式
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $type       = [
        // 设置时间戳类型（整型）
        'create_time'     => 'timestamp',
        'update_time'     => 'timestamp',
        'last_login_time' => 'timestamp',
        'expire_time' => 'timestamp',

    ];

    //自动完成
    protected $insert = [
    	'create_time',
    	'update_time',
    ];

    protected $update = ['update_time'];

    // 属性修改器
    protected function setCreateTimeAttr($value, $data)
    {
        return time();
    }

    // 属性修改器
    protected function setUpdateTimeAttr($value, $data)
    {
        return time();
    }

    // 属性修改器
    protected function setLastLoginTimeAttr($value, $data)
    {
        return time();
    }

    protected function getLastLoginTimeAttr($datetime)
    {
        return date('Y-m-d H:i:s', $datetime);
    }

    protected function setExpireTimeAttr($value, $data)
    {
        return time();
    }
    protected function getExpireTimeAttr($datetime)
    {
        return date('Y-m-d H:i:s', $datetime);
    }

    // status属性读取器
    protected function getStatusAttr($value)
    {
        $status = [-1 => '删除', 0 => '禁用', 1 => '正常', 2 => '待审核'];
        return $status[$value];
    }




    // // create_time读取器设置时间格式
    // protected function getCreateTimeAttr($datetime)
    // {
    //     return date('Y-m-d H:i:s', $datetime);
    // }
    // // update_time读取器设置时间格式
    // protected function getUpdateTimeAttr($datetime)
    // {
    //     return date('Y-m-d H:i:s', $datetime);
    // }
}