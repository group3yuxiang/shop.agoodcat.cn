<?php
namespace app\index\controller;

use app\admin\model\Category;
use app\admin\model\Product;
use think\Controller;

class Index extends Controller{

    public function index() {
        $category_model = new Category;
        $product_model = new Product;
        $category_list = $category_model->getList(0, 0, 'sort');
        $product_list = $product_model->getList(0, 0, 'sort');
        foreach ($category_list as $k => $v) {
            $data[$k]['name'] = $v['name'];
            foreach ($product_list as $k1 => $v1) {
                if ($v['id'] == $v1['category_id']) {
                    $data[$k]['product'][] = $v1;
                }
            }
            if (empty($data[$k]['product'])) {
                unset($data[$k]);
            }
        }
        $data = array_values($data);
        $this->assign([
            'data' => $data
        ]);
        //echo json_encode($data);die;
        //$this->view
        return $this->fetch('index');
    }
}
