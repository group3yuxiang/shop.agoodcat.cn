<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/9/5
 * Time: 12:20
 */
namespace app\admin\controller;

use app\admin\model\Category as CategoryModel;
use think\Controller;

class Category extends Controller{

    public function index() {
        $model = new CategoryModel;
        $list = $model->getList();
        //echo json_encode($list);die;
        $this->assign([
            'admin' => cookie('user_name'),
            'list' => $list
        ]);
        return $this->fetch('list');
    }

    public function update($id = 0) {
        if ($id > 0) {
            $info = CategoryModel::get($id);
            $this->assign('info', $info);
        }
        return $this->fetch('info');
    }

    public function doUpdate() {
        $model = new CategoryModel;
        $id = request()->param('save_id', 0);
        if ($id > 0) {
            $condition = ['id' => $id];
        }
        //echo json_encode($_POST);die;
        // 过滤post数组中的非数据表字段数据
        $nret = $model->allowField(true)->save($_POST, $condition);
        //var_dump($nret);die;
        $this->redirect("Category/index");
    }

    public function delete($id = 0) {
        CategoryModel::destroy($id);
        $this->redirect("Category/index");
    }
}