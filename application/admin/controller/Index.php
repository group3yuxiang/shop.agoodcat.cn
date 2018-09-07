<?php
namespace app\admin\controller;

use app\admin\model\Product;
use think\Controller;
use app\admin\model\Category;
use think\Cookie;
use think\Image;

class Index extends Controller{

    public function _initialize(){
        $this->assign([
            'admin' => cookie('user_name'),
        ]);
        parent::_initialize();
    }

    public function index() {
        if (cookie('user_id')) {
            $this->redirect(url('index/site'));
        }
        return $this->fetch('login');
    }

    public function login() {
        /*
        $params = request()->param();
        $user = Admin::get([
            'name' => $params['name'],
            'password' => $params['password']
        ]);
        if($user){
            //$this->redirect("Index/index");
            return $this->fetch('index');
        }else{
            return $this->error('登录失败');
        }
        */
        $param = input('post.');
        //var_dump($param);
        if(empty($param['name'])){

            $this->error('用户名不能为空');
        }

        if(empty($param['password'])){

            $this->error('密码不能为空');
        }

        // 验证用户名
        $has = db('admin')->where('name', $param['name'])->find();
        if(empty($has)){

            $this->error('用户名密码错误');
        }

        // 验证密码
        if($has['password'] != $param['password']){

            $this->error('用户名密码错误');
        }

        // 记录用户登录信息
        cookie('user_id', $has['id'], 3600);  // 一个小时有效期
        cookie('user_name', $has['name'], 3600);
        cookie('app_module', 'jddq', 3600);
        $this->redirect(url('index/site'));
    }

    public function site() {
        $model = new Product;
        $list = $model->getList();
        //echo json_encode($list);die;
        $category_model = new Category;
        $category_list = $category_model->getList();
        $category_list = array_column($category_list, 'name', 'id');
        foreach ($list as $k => $v) {
            $list[$k]['category'] = '';
            if (isset($category_list[$v['category_id']])) {
                $list[$k]['category'] = $category_list[$v['category_id']];
            }
        }
        // json_encode($list);die;
        $this->assign([
            'admin' => cookie('user_name'),
            'list' => $list,
            'category_list' => $category_list,
            'module' => APP_MODULE
        ]);
        //echo APP_MODULE;die;
        return $this->fetch('index');
        //echo "您好： " . cookie('user_name') . ', <a href="' . url('index/loginout') . '">退出</a>';
    }

    public function loginOut() {
        cookie('user_id', null);
        cookie('app_module', null);
        $this->redirect(url('index/index'));
    }

    public function product($id = 0, $module = '') {
        //$info = new Product;
        if ($id > 0) {
            $info = Product::get($id);
            $this->assign('info', $info);
        }
        $category_model = new Category;
        $this->assign([
            'category_list' => $category_model->getList(),
            'module' => $module
        ]);
        return $this->fetch('productInfo');
    }

    public function updateProduct() {
        //var_dump($_POST);die;
        // 获取表单上传文件 例如上传了001.jpg
        $save_data = $_POST;
        $save_data['module'] = cookie('app_module');
        $id = request()->param('save_id', 0);
        $file = request()->file('logo');
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            //$save_data['logo'] =  $info->getSaveName();
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $image = Image::open($info->getRealPath());//生成缩略图
                $image->thumb(309, 302,Image::THUMB_FIXED)->save(ROOT_PATH . 'public' . DS . 'uploads/' . $info->getSaveName());
                // 成功上传后 获取上传信息
                // 输出 jpg
                //var_dump($info);die;
                //$save_data['logo'] =  $info->getExtension();
                $save_data['logo'] =  $info->getSaveName();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                //echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                //echo $info->getFilename();
            }else{
                // 上传失败获取错误信息
                $this->error($file->getError());
            }
        }
        if ($id > 0) {
            $condition = ['id' => $id];
        }
        $model = new Product;
        // 过滤post数组中的非数据表字段数据
        $nret = $model->allowField(true)->save($save_data, $condition);
        //var_dump($nret);die;
        $this->redirect("index/site");
    }

    public function deleteProduct($id = 0) {
        Product::destroy($id);
        $this->redirect("index/site");
    }
}
