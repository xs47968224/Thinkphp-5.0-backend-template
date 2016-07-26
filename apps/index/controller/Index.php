<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    public function index()
    {
    	return 111;
    }

    public function edit($name = 'thinkphp')
    {
         // 插入记录
		$result = Db::execute('insert into think_data (id, name ,status) values (5, "thinkphp",1)');
		dump($result);
		return 0;
    }
}
