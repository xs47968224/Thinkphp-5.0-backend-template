<?php
namespace app\admin\model;

use think\Model;

class Posts extends Model
{

    // 设置完整的数据表（包含前缀）
    protected $table = 'think_posts';

    // 关闭自动写入时间戳
    //protected $autoWriteTimestamp = false;

    //默认时间格式
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $type       = [
        // 设置时间戳类型（整型）
        'create_time'     => 'timestamp',
        'update_time'     => 'timestamp',
    ];

    //自动完成
    protected $insert = [
    	'create_time',
    	'update_time',
    ];

    protected $update = ['update_time'];

    // status属性读取器
    protected function getStatusAttr($value)
    {
        $status = [-1 => '删除', 0 => '草稿', 1 => '发布',2 => '待审核'];
        return $status[$value];
    }

    // comment status属性读取器
    protected function getCommentStatusAttr($value)
    {
        $status = ['opened'=>'打开','closed' => '关闭'];
        return $status[$value];
    }

    // comment count属性读取器
    protected function getCommentCountAttr($value)
    {
        return $value ? $value : 0;
    }

    // comment count属性读取器
    protected function getPostAuthorAttr($value)
    {
        $admins = Administrator::where('status',1)->column('nickname','id');
        return $admins[$value];
    }

}

