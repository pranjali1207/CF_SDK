<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html >
<head >
    <meta charset="utf-8" >
    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit" >
    <meta http-equiv="X-UA-Compatible" content="IE=edge" >
    <meta name="viewport" content="width=device-width, initial-scale=1" >

    <!-- HTML5 shim for IE8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js" ></script >
    <![endif]-->

    <link href="/public/simpleboot/themes/<?php echo C('SP_ADMIN_STYLE');?>/theme.min.css" rel="stylesheet" >
    <link href="/public/simpleboot/css/simplebootadmin.css" rel="stylesheet" >
    <link href="/public/js/artDialog/skins/default.css" rel="stylesheet" />
    <link href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" >
    <style >
        .length_3 {
            width: 180px;
        }

        form .input-order {
            margin-bottom: 0px;
            padding: 3px;
            width: 40px;
        }

        .table-actions {
            margin-top: 5px;
            margin-bottom: 5px;
            padding: 0px;
        }

        .table-list {
            margin-bottom: 0px;
        }

        .select2-container .select2-dropdown {
            z-index: 99999999;
        }
    </style >
    <!--[if IE 7]>
    <link rel="stylesheet" href="/public/simpleboot/font-awesome/4.4.0/css/font-awesome-ie7.min.css" >
    <![endif]-->
    <script type="text/javascript" >
        //全局变量
        var GV = {
            DIMAUB : "/",
            JS_ROOT: "public/js/",
            TOKEN  : ""
        };
    </script >
    <!-- Le javascript
        ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/public/js/jquery.js" ></script >
    <script src="/public/js/wind.js" ></script >
    <script src="/public/simpleboot/bootstrap/js/bootstrap.min.js" ></script >
    <!--<script src="/public/3rd/datatable/js/jquery.dataTables.js" ></script >-->
    <!--<link href="/public/3rd/datatable/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" >-->
    <?php if(APP_DEBUG): ?><style >
            #think_page_trace_open {
                z-index: 9999;
            }
        </style ><?php endif; ?>

    <link rel="stylesheet" type="text/css" href="/public/select2/css/select2.min.css" />
    <script src="/public/select2/js/select2.min.js" ></script >
    <script type="text/javascript" src="/public/select2/js/i18n/zh-CN.js" ></script >
    <script >
        $(document).ready(function () {
            if($.isFunction($.select2)){
                $(".select_2").select2({
                    language: "zh-CN"
                });
            }

//$('table').dataTable({"info": false,"paging": false,"lengthChange": false,"searching": false,"ordering": false});

            $(".form-search select").change(function () {
//        $("input[name='submit']").click();
                $("input[value='搜索']").click();
            });
        });

    </script >

    <script src="/public/3rd/layer/layer.js" ></script >
    <script src="/public/huoshu/share.js" ></script >
</head>
<body >
<div class="wrap" >
    <ul class="nav nav-tabs" >
        <li ><a href="<?php echo U('menu/index');?>" ><?php echo L('ADMIN_MENU_INDEX');?></a ></li >
        <li ><a href="<?php echo U('menu/add');?>" ><?php echo L('ADMIN_MENU_ADD');?></a ></li >
    </ul >
    <form method="post" class="form-horizontal js-ajax-form" action="<?php echo U('Menu/edit_post');?>" >
        <fieldset >
            <div class="control-group" >
                <label class="control-label" >上级:</label >
                <div class="controls" >
                    <select name="parentid" >
                        <option value="0" >作为一级菜单</option >
                        <?php echo ($select_categorys); ?>
                    </select >
                </div >
            </div >
            <div class="control-group" >
                <label class="control-label" >名称:</label >
                <div class="controls" >
                    <input type="text" name="name" value="<?php echo ($data["name"]); ?>" >
                    <span class="form-required" >*</span >
                </div >
            </div >
            <div class="control-group" >
                <label class="control-label" >应用:</label >
                <div class="controls" >
                    <input type="text" name="app" value="<?php echo ($data["app"]); ?>" >
                    <span class="form-required" >*</span >
                </div >
            </div >
            <div class="control-group" >
                <label class="control-label" >控制器:</label >
                <div class="controls" >
                    <input type="text" name="model" value="<?php echo ($data["model"]); ?>" >
                    <span class="form-required" >*</span >
                </div >
            </div >
            <div class="control-group" >
                <label class="control-label" >方法:</label >
                <div class="controls" >
                    <input type="text" name="action" value="<?php echo ($data["action"]); ?>" >
                    <span class="form-required" >*</span >
                </div >
            </div >
            <div class="control-group" >
                <label class="control-label" >参数:</label >
                <div class="controls" >
                    <input type="text" name="data" value="<?php echo ($data["data"]); ?>" > 例:id=3&amp;p=3
                </div >
            </div >
            <div class="control-group" >
                <label class="control-label" >图标:</label >
                <div class="controls" >
                    <input type="text" name="icon" value="<?php echo ($data["icon"]); ?>" >
                    <a href="http://www.thinkcmf.com/font/icons" target="_blank" >选择图标</a > 不带前缀fa-，如fa-user => user
                </div >
            </div >
            <div class="control-group" >
                <label class="control-label" >备注:</label >
                <div class="controls" >
                    <textarea name="remark" rows="5" cols="57" style="width: 500px;" ><?php echo ($data["remark"]); ?></textarea >
                </div >
            </div >
            <div class="control-group" >
                <label class="control-label" >状态:</label >
                <div class="controls" >
                    <select name="status" >
                        <option value="1" >显示</option >
                        <?php $status_selected=empty($data['status'])?"selected":""; ?>
                        <option value="0" <?php echo ($status_selected); ?> >隐藏</option >
                    </select >
                </div >
            </div >
            <div class="control-group" >
                <label class="control-label" >类型:</label >
                <div class="controls" >
                    <select name="type" >
                        <option value="1" >权限认证+菜单</option >
                        <?php $type_selected=empty($data['type'])?"selected":""; ?>
                        <option value="0" <?php echo ($type_selected); ?> >只作为菜单</option >
                    </select >
                    注意：“权限认证+菜单”表示加入后台权限管理，纯碎是菜单项请不要选择此项。
                </div >
            </div >
        </fieldset >
        <div class="form-actions" >
            <input type="hidden" name="id" value="<?php echo ($data["id"]); ?>" />
            <button type="submit" class="btn btn-primary js-ajax-submit" ><?php echo L('SAVE');?></button >
            <button class="btn js-ajax-close-btn" type="submit" ><?php echo L('CLOSE');?></button >
        </div >
    </form >
</div >
<script src="/public/js/common.js" ></script >
<script >
    $(function () {
        $(".js-ajax-close-btn").on('click', function (e) {
            e.preventDefault();
            Wind.use("artDialog", function () {
                art.dialog({
                    id        : "question",
                    icon      : "question",
                    fixed     : true,
                    lock      : true,
                    background: "#CCCCCC",
                    opacity   : 0,
                    content   : "您确定需要关闭当前页面嘛？",
                    ok        : function () {
                        setCookie('refersh_time_admin_menu_index', 1);
                        window.close();
                        return true;
                    }
                });
            });
        });
    });
</script >
</body >
</html>