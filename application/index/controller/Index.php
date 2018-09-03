<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->assign([

        ]);
        //$this->view
        return $this->fetch('index');
    }
}
