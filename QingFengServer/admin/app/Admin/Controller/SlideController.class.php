<?php
namespace Admin\Controller;

use Common\Controller\AdminbaseController;

class SlideController extends AdminbaseController {
    protected $slide_model;
    protected $slidecat_model;

    function _initialize() {
        parent::_initialize();
        $this->slide_model = D("Common/Slide");
        $this->slidecat_model = D("Common/SlideCat");
    }

    function index() {
        $this->_game();
        $cates = array(
            array("cid" => "0", "cat_name" => "默认分类"),
        );
        if(I('is_wap') == 1) {
            $_map['cat_idname'] = ['in', ['wapindex', 'welfare_game', 'wap_newgame', 'wap_hotgame']];
            $wap_ids = $this->slidecat_model->where($_map)->getField("cid", true);
            if (!empty($wap_ids)) {
                $where1['cid'] = ['in', $wap_ids];
            }
        }elseif(I('is_app') == 1){
            $_map['cat_idname'] = ['in', ['app_slide', 'app_hotslide', 'app_newslide', 'app_welfareslide','texthome','ios_app_hotslide', 'ios_app_newslide', 'ios_app_welfareslide']];
            $app_ids = $this->slidecat_model->where($_map)->getField("cid", true);
            if (!empty($app_ids)) {
                $where1['cid'] = ['in', $app_ids];
            }
        }else{
            $where1 = "cat_status!=0";
        }

        $categorys = $this->slidecat_model->field("cid,cat_name")->where($where1)->select();
        //自动加载配置
        $init_data = ['wap公益广告图', 'wap新游广告图', 'wap热门广告图','iosAPP公益广告图', 'iosAPP新游广告图', 'iosAPP热门广告图'];//'wap精品广告图',
        foreach ($categorys as $key => $value) {
            if (in_array($value['cat_name'], $init_data)) {
                unset($init_data[array_search($value['cat_name'], $init_data)]);
            }
        }
        if (!empty($init_data) && count($init_data) > 0 ) {
            if(I('is_wap') == 1) {
                foreach ($init_data as $v) {
                    if ($v == 'wap公益广告图') {
                        $cat_idname = 'welfare_game';
                    } elseif ($v == 'wap新游广告图') {
                        $cat_idname = 'wap_newgame';
                    } elseif ($v == 'wap热门广告图') {
                        $cat_idname = 'wap_hotgame';
                    }else {
                        continue;
                    }
//                if ($v == 'wap精品广告图') {
//                    $cat_idname = 'delicate_game';
//                } elseif ($v == 'wap公益广告图') {
//                    $cat_idname = 'welfare_game';
//                } else {
//                    continue;
//                }
                    $data = [
                        'cat_name'   => $v,
                        'cat_idname' => $cat_idname,
                        'cat_remark' => $v,
                        'cat_type'   => 3,
                    ];
                    $this->slidecat_model->add($data);
                }
            }elseif(I('is_app') == 1){
                foreach ($init_data as $v) {
                    if ($v == 'iosAPP热门广告图') {
                        $cat_idname = 'ios_app_hotslide';
                    } elseif ($v == 'iosAPP新游广告图') {
                        $cat_idname = 'ios_app_newslide';
                    }elseif ($v == 'iosAPP公益广告图') {
                        $cat_idname = 'ios_app_welfareslide';
                    }else {
                        continue;
                    }
                    $data = [
                        'cat_name'   => $v,
                        'cat_idname' => $cat_idname,
                        'cat_remark' => $v,
                        'cat_type'   => 3,
                    ];
                    $this->slidecat_model->add($data);
                }
            }

        }
        //自动加载结束
        if ($categorys) {
            $categorys = array_merge($cates, $categorys);
        } else {
            $categorys = $cates;
        }
        $this->assign("categorys", $categorys);

        $cid = 0;
        $p_cid = I('cid');

        if(I('is_wap') == 1) {
            $_map['cat_idname'] = ['in',['wapindex','welfare_game','wap_newgame','wap_hotgame']];
            $wap_ids = $this->slidecat_model->where($_map)->getField("cid", true);

            if(!empty($wap_ids)) {
                $where['slide_cid'] = ['in', $wap_ids];
            }
            
        }elseif(I('is_app') == 1) {
            $_map['cat_idname'] = ['in', ['app_slide', 'app_hotslide', 'app_newslide', 'app_welfareslide','texthome','ios_app_hotslide', 'ios_app_newslide', 'ios_app_welfareslide']];
            $app_ids = $this->slidecat_model->where($_map)->getField("cid",true);
            if(!empty($app_ids)) {
                $where['slide_cid'] = ['in',$app_ids];
            }
        }else{
            if (isset($p_cid) && $p_cid != "") {
                $cid = $p_cid;
                $where = "slide_cid=$cid";
            }
        }

        $this->assign("slide_cid", $cid);
        $slides = $this->slide_model->where($where)->order("listorder ASC")->select();
        $this->assign('slides', $slides);
        $this->display();
    }

    function add() {
        $this->_game(true, '', 2, '', 2);
        $categorys = $this->slidecat_model->field("cid,cat_name")->where("cat_status!=0")->select();
        $this->assign("categorys", $categorys);
        $this->display();
    }

    function add_post() {
        if (IS_POST) {
            $_POST['type_id'] = 2;
            $_POST['target_id'] = $_POST['app_id'];
            if ($this->slide_model->create()) {
                $_POST['slide_pic'] = sp_asset_relative_url($_POST['slide_pic']);
                if ($this->slide_model->add() !== false) {
                    $this->success("添加成功！", U("slide/index"));
                } else {
                    $this->error("添加失败！");
                }
            } else {
                $this->error($this->slide_model->getError());
            }
        }
    }

    function edit() {
        $this->_game(true, '', 2, '', 2);
        $categorys = $this->slidecat_model->field("cid,cat_name")->where("cat_status!=0")->select();

        $id = intval(I("get.id"));
        $slide = $this->slide_model->where("slide_id=$id")->find();
        $this->assign($slide);
        $this->assign("categorys", $categorys);
        $this->display();
    }

    function edit_post() {
        if (IS_POST) {
            $_POST['type_id'] = 2;
            $_POST['target_id'] = $_POST['app_id'];
            if ($this->slide_model->create()) {
                $_POST['slide_pic'] = sp_asset_relative_url($_POST['slide_pic']);
                if ($this->slide_model->save() !== false) {
                    $this->success("保存成功！", U("slide/index"));
                } else {
                    $this->error("保存失败！");
                }
            } else {
                $this->error($this->slide_model->getError());
            }
        }
    }

    function delete() {
        if (isset($_POST['ids'])) {
            $ids = implode(",", $_POST['ids']);
            $data['slide_status'] = 1;
            if ($this->slide_model->where("slide_id in ($ids)")->delete() !== false) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        } else {
            $id = intval(I("get.id"));
            if ($this->slide_model->delete($id) !== false) {
                $this->success("删除成功！");
            } else {
                $this->error("删除失败！");
            }
        }
    }

    function toggle() {
        if (isset($_POST['ids']) && $_GET["display"]) {
            $ids = implode(",", $_POST['ids']);
            $data['slide_status'] = 2;
            if ($this->slide_model->where("slide_id in ($ids)")->save($data) !== false) {
                $this->success("显示成功！");
            } else {
                $this->error("显示失败！");
            }
        }
        if (isset($_POST['ids']) && $_GET["hide"]) {
            $ids = implode(",", $_POST['ids']);
            $data['slide_status'] = 1;
            if ($this->slide_model->where("slide_id in ($ids)")->save($data) !== false) {
                $this->success("隐藏成功！");
            } else {
                $this->error("隐藏失败！");
            }
        }
    }

    //隐藏
    function ban() {
        $id = intval($_GET['id']);
        $data['slide_status'] = 1;
        if ($id) {
            $rst = $this->slide_model->where("slide_id in ($id)")->save($data);
            if ($rst) {
                $this->success("轮播图隐藏成功！");
            } else {
                $this->error('轮播图隐藏失败！');
            }
        } else {
            $this->error('数据传入失败！');
        }
    }

    //显示
    function cancelban() {
        $id = intval($_GET['id']);
        $data['slide_status'] = 2;
        if ($id) {
            $result = $this->slide_model->where("slide_id in ($id)")->save($data);
            if ($result) {
                $this->success("轮播图启用成功！");
            } else {
                $this->error('轮播图启用失败！');
            }
        } else {
            $this->error('数据传入失败！');
        }
    }

    //排序
    public function listorders() {
        $status = parent::_listorders($this->slide_model);
        if ($status) {
            $this->success("排序更新成功！");
        } else {
            $this->error("排序更新失败！");
        }
    }
}