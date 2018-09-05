<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/9/4
 * Time: 21:17
 */
namespace app\admin\model;
use think\Model;

class Product extends Model{
    protected $pk = 'id';
    protected $order = 'id';

    public function getList($page = 1, $pageSize = 30, $order = '') {
        if ($order) {
            $this->order = $order;
        }
        //todo 分页
        $data = $this->all(function($query){
            //$query->where('display', 1)->limit(3,5)->order('id', 'asc');
            $query->where('display', 1)->order($this->order, 'desc');
        });

        //$data = $this->all(['display' => 1]);
        return collection($data)->toArray();
    }
}