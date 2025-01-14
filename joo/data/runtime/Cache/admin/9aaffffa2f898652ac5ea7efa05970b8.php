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
<!--清理缓存-->
<div class="subnav">
    <h1 class="title_2 line_x"><?php echo L('clear_cache');?></h1>
</div>
<div class="common-form pad_lr_10">
	<form id="info_form" action="<?php echo u('cache/clear');?>" method="post">
		<table width="100%" cellpadding="2" cellspacing="1" class="table_form">
			<tr>
				<th width="30"></th>
				<td width="120"><label><input type="checkbox" value="field" name="type" class="mr5" data-uri="<?php echo U('cache/clear', array('type'=>'field'));?>"> <?php echo L('field_cache');?></label></td>
				<td><span class="gray mr10"><?php echo L('field_cache_desc');?></span><span id="field_ifm"></span></td>
			</tr>
			<tr>
				<th></th>
				<td><label><input type="checkbox" value="tpl" name="type" class="mr5" data-uri="<?php echo U('cache/clear', array('type'=>'tpl'));?>"> <?php echo L('tpl_cache');?></label></td>
				<td><span class="gray mr10"><?php echo L('tpl_cache_desc');?></span><span id="tpl_ifm"></span></td>
			</tr>
			<tr>
				<th></th>
				<td><label><input type="checkbox" value="data" name="type" class="mr5" data-uri="<?php echo U('cache/clear', array('type'=>'data'));?>"> <?php echo L('data_cache');?></label></td>
				<td><span class="gray mr10"><?php echo L('data_cache_desc');?></span><span id="data_ifm"></span></td>
			</tr>
			<tr>
				<th></th>
				<td><label><input type="checkbox" value="runtime" name="type" class="mr5" data-uri="<?php echo U('cache/clear', array('type'=>'runtime'));?>"> <?php echo L('runtime_cache');?></label></td>
				<td><span class="gray mr10"><?php echo L('runtime_cache_desc');?></span><span id="runtime_ifm"></span></td>
			</tr>
			<tr>
				<th></th>
				<td><label><input type="checkbox" value="logs" name="type" class="mr5" data-uri="<?php echo U('cache/clear', array('type'=>'logs'));?>"> <?php echo L('logs_cache');?></label></td>
				<td><span class="gray mr10"><?php echo L('logs_cache_desc');?></span><span id="logs_ifm"></span></td>
			</tr>
			<tr>
				<th></th>
				<td><label><input type="checkbox" value="js" name="type" class="mr5" data-uri="<?php echo U('cache/clear', array('type'=>'js'));?>"> JS缓存文件</label></td>
				<td><span class="gray mr10">修改过JS文件需要更新才会生效</span><span id="js_ifm"></span></td>
			</tr>
            <tr>
            	<th></th>
                <td><input type="button" id="J_clear_cache" class="btn btn_submit" value="<?php echo L('clear');?>"/></td>
                <td></td>
            </tr>
		</table>
	</form>
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
<script>
$(function(){
    $('#J_clear_cache').live('click', function(){
        $('input[name="type"]:checked').each(function(){
            var type = $(this).val();
                uri = $(this).attr('data-uri');
            $('#'+type+'_ifm').html(lang.clear_wait);
            $.getJSON(uri, {type:type}, function(result){
                $('#'+type+'_ifm').addClass('onCorrect').html(lang.clear_success);
            });
        });
    });
});
</script>
</body>
</html>