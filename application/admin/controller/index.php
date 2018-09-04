<?php
namespace app\admin\controller;

use think\Controller;
use app\admin\model\Admin;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch('login');
    }

    public function login($name = '', $password = '') {
        $user = Admin::get([
            'name' => $name,
            'password' => $password
        ]);
        if($user){
            echo '登录成功'.$name.md5($name);
        }else{
            return $this->error('登录失败');
        }
    }
}