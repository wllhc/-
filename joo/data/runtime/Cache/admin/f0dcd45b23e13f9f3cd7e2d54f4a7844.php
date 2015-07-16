<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<link href="__STATIC__/css/admin/style.css" rel="stylesheet"/>
	<title><?php echo L('website_manage');?></title>
	<script>
	var URL = '__URL__';
	var SELF = '__SELF__';
	var ROOT_PATH = '__ROOT__';
	var APP	 =	 '__APP__';
	//语言项目
	var lang = new Object();
	<?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?>
	</script>
</head>

<body>
<div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div>
<?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav">
    <div class="content_menu ib_a blue line_x">
    	<?php if(!empty($big_menu)): ?><a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; ?>
        <?php if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?>
        <a href="<?php echo U($val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo L($val['name']);?></em></a><?php endforeach; endif; else: echo "" ;endif; endif; ?>
    </div>
</div><?php endif; ?>
<!--接口列表-->
<div class="pad_10">
    <div class="J_tablelist table_list" data-acturi="<?php echo U('item_site/ajax_edit');?>">
        <table width="100%" cellspacing="0">
            <thead>
            <tr>
                <th width="40"><input type="checkbox" name="checkall" class="J_checkall"></th>
      			<th><?php echo L('item_site_code');?></th>
                <th><?php echo L('item_site_name');?></th>
                <th><?php echo L('item_site_domain');?></th>
                <th><span data-tdtype="order_by" data-field="ordid"><?php echo L('sort_order');?></span></th>
                <th><?php echo L('enabled');?></th>
                <th><?php echo L('item_site_desc');?></th>
                <th><?php echo L('author');?></th>
                <th width="100"><?php echo L('operations_manage');?></th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i; if($val["status"] > -1): ?><tr>
                <td align="center"><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>
                <td align="center"><?php echo ($val["code"]); ?></td>
                <td align="center"><span data-tdtype="edit" data-field="name" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["name"]); ?></span></td>
                <td align="center"><span data-tdtype="edit" data-field="domain" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["domain"]); ?></span></td>
                <td align="center"><span data-tdtype="edit" data-field="ordid" data-id="<?php echo ($val["id"]); ?>" class="tdedit"><?php echo ($val["ordid"]); ?></span></td>
                <td align="center">
                    <img data-tdtype="toggle" data-field="status" data-id="<?php echo ($val["id"]); ?>" data-value="<?php echo ($val["status"]); ?>" src="__STATIC__/images/admin/toggle_<?php if($val["status"] == 0): ?>disabled<?php else: ?>enabled<?php endif; ?>.gif" />
                </td>
                <td><?php echo ($val["desc"]); ?></td>
                <td><?php echo ($val["author"]); ?></td>
                <td align="center">
                    <a href="javascript:;" class="J_showdialog" data-uri="<?php echo U('item_site/edit', array('id'=>$val['id']));?>" data-title="<?php echo L('edit');?> - <?php echo ($val["name"]); ?>"  data-id="edit"><?php echo L('edit');?></a> | 
                    <a href="javascript:;" class="J_confirmurl" data-acttype="ajax" data-uri="<?php echo U('item_site/delete', array('id'=>$val['id']));?>" data-msg="<?php echo sprintf(L('confirm_uninstall_one'),$val['name']);?>"><?php echo L('uninstall');?></a>
                </td>
            </tr>
            <?php else: ?>
            <tr>
                <td align="center"></td>
                <td align="center"><?php echo ($val["code"]); ?></td>
                <td align="center"><?php echo ($val["name"]); ?></td>
                <td align="center"><?php echo ($val["domain"]); ?></td>
                <td align="center"></td>
                <td align="center"></td>
                <td><?php echo ($val["desc"]); ?></td>
                <td><?php echo ($val["author"]); ?></td>
                <td align="center">
                    <a href="javascript:;" class="J_showdialog" data-uri="<?php echo U('item_site/install', array('code'=>$val['code']));?>" data-title="<?php echo L('install');?> - <?php echo ($val["name"]); ?>" data-id="install"><?php echo L('install');?></a>
                </td>
            </tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>
    <div class="btn_wrap_fixed">
        <label class="select_all"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>
        <input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('item_site/delete');?>" data-name="id" data-msg="<?php echo L('confirm_uninstall');?>" value="<?php echo L('uninstall');?>" />
        <div id="pages"><?php echo ($page); ?></div>
    </div>
</div>
<script src="__STATIC__/js/jquery/jquery.js"></script>
<script src="__STATIC__/js/jquery/plugins/jquery.tools.min.js"></script>
<script src="__STATIC__/js/jquery/plugins/formvalidator.js"></script>
<script src="__STATIC__/js/pinphp.js"></script>
<script src="__STATIC__/js/admin.js"></script>
<script>
//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script>

<?php if(isset($list_table)): ?><script src="__STATIC__/js/jquery/plugins/listTable.js"></script>
<script>
$(function(){
	$('.J_tablelist').listTable();
});
</script><?php endif; ?>
</body>
</html>