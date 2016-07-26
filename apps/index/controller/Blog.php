<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
class Blog
{
    public function index(){
        return 111;
    }

    public function get($id = 5)
    {

    }


    public function edit($name = 'thinkphp')
    {

    }

    public function update($id = 5){
        // 更新记录

    }

    public function delete($id = 5){
    }

    public function show(){
        $list = Db::connect('db2')->table('wp_posts')
    ->where('id', 2)
    ->select();
        dump($list);
    }
}