<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

return [
     // 全局变量规则定义
    '__pattern__'         => [
        'name'  => '\w+',
        'id'    => '\d+',
        'year'  => '\d{4}',
        'month' => '\d{2}',
    ],
    // 路由规则定义
    'admin/login/'                               => 'admin/index/login',
    'admin/login_action/'                        => 'admin/index/login_action',
    'admin/lost_password/'                       => 'admin/index/lost_password',
    'admin/logout/'                              => 'admin/index/logout',

    'admin/administrator/:id'                    => 'admin/administrator/read',
    'admin/administrator/update/:id'             => 'admin/administrator/update',
    'admin/administrator/delete/:id'             => 'admin/administrator/delete',
    'admin/administrator/delete_image/:id'       => 'admin/administrator/delete_image',
    'admin/administrator/update_expire_time/:id' => 'admin/administrator/update_expire_time',

    'admin/posts/:id'                            => 'admin/posts/read',
    'admin/posts/update/:id'                     => 'admin/posts/update',
    'admin/posts/delete/:id'                     => 'admin/posts/delete',
    'admin/posts/delete_image/:id'               => 'admin/posts/delete_image',

];
