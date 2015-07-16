<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
    <head>
    <meta charset="utf-8" />
<title><?php echo ($page_seo["title"]); ?> - JOO</title>
<meta name="keywords" content="<?php echo ($page_seo["keywords"]); ?>" />
<meta name="description" content="<?php echo ($page_seo["description"]); ?>" />
<!--css file="__STATIC__/css/default/base.css" />
<link rel="stylesheet" type="text/css" href="__STATIC__/css/default/style.css" />
<css file="__STATIC__/css/default/content.css" /-->
<link rel="stylesheet" type="text/css" href="__STATIC__/css/default/css.css" />
<script src="__STATIC__/js/jquery/jquery.js"></script>
<script src="__STATIC__/js/message.js"></script>

    <link rel="stylesheet" type="text/css" href="__STATIC__/css/default/space.css" />
</head>
<body>
    <div class="wrap">
        <!--头部开始-->
<div class="header">
        <div class="header_cont">
            <div class="logo"><a title="<?php echo C('pin_site_name');?>" href="<?php echo U('index/index');?>"></a></div>
            <ul class="nav">
                <?php $tag_nav_class = new navTag;$data = $tag_nav_class->lists(array('type'=>'lists','style'=>'main','cache'=>'0','return'=>'data',)); if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li>
                    <a href="<?php echo ($val["link"]); ?>" <?php if($val["target"] == 1): ?>target="_blank"<?php endif; ?>><?php echo ($val["name"]); ?></a>
					<?php if($val["alias"] == 'book'): ?><span></span>
                    <ul class="subnav">
                    	<li><a href="#">推按</a></li>
                        <li><a href="#">热度排行</a></li>
                        <li><a href="#">心情日志</a></li>
                        <li><a href="#">走走</a></li>
                    </ul><?php endif; ?>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                <pin:nav>
            </ul>
            <ul class="nav rightnav">
                <li>
                    <a href="<?php echo U('upload/index');?>" class="topaddbtn">添加</a>
                    <!--ul class="subnav">
                        <li><a href="<?php echo U('upload/index');?>">新照片</a></li>
                        <li><a href="#">新专辑</a></li>
                        <li><a href="#">新关注</a></li>
                    </ul-->
                </li>
                <?php if(!empty($visitor)): ?><li>
                        <a href="<?php echo U('space/index');?>" class="topusrbtn clearfix"><b><img class="mb_avt r3" src="<?php echo avatar($visitor['id'], 24);?>"></b><span><?php echo ($visitor["username"]); ?></span></a>
                        <ul class="subnav rnav">
                            <li><a href="<?php echo U('space/index');?>">我的空间</a></li>
                            <!--li><a href="<?php echo U('space/album');?>">专辑</a></li>
                            <li><a href="<?php echo U('space/index');?>">收藏</a></li>
                            <li><a href="<?php echo U('space/follow');?>">关注</a></li>
                            <li><a href="<?php echo U('space/fans');?>">粉丝</a></li-->
                            <li class="navline"></li>
                            <li><a href="<?php echo U('user/index');?>">设置</a></li>
                            <li><a href="<?php echo U('user/logout');?>">退出</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="#" class="topusrbtn clearfix"><b><img src="temp/avatar.jpg" /></b><span>您好,游客</span></a>
                        <ul class="subnav tlogin">
                            <li>
                                <form id="J_login_form" action="<?php echo U('user/login');?>" method="post">
                                <div>
                                    <p><input type="text" name="username" id="J_name" class="ttext"  placeholder="用户名"/></p>
                                    <p><input type="password" name="password" id="J_pass" class="ttext" placeholder="密码" /></p>
                                    <p style="text-align:right"><input type="submit" class="btngreen" value="登录" /></p>
                                    <input type="hidden" name="ret_url" value="<?php echo U('index/index');?>" />
                                </div>
                                </form>
                            </li>
                            <li class="navline"></li>
                            <li class="reg">
                                <p style="text-align:right">还没有JOO账号?<a href="<?php echo U('user/register');?>">创建一个</a></p>
                            </li>
                        </ul>
                    </li>
                    <!--li class="me"><a href="<?php echo U('user/register');?>" class="register_btn"><?php echo L('register_now');?></a></li--><?php endif; ?>
                <!--li><a href="#">摄影师</a></li>
                <li><a href="#">展会</a></li-->
            </ul>
            <div class="tsearch">
            	<input type="text" class="headsearch" />
            </div>
        </div>
    </div>





<!--div class="header_wrap pt10">
    <div id="J_m_head" class="m_head clearfix">
        <div class="head_logo fl"><a href="__ROOT__/" class="logo_b fl" title="<?php echo C('pin_site_name');?>"><?php echo C('pin_site_name');?></a></div>
        <div class="head_user fr">
            <?php if(!empty($visitor)): ?><ul class="head_user_op">
                <li class="mr10"> 
                    <a class="J_shareitem_btn share_btn" href="javascript:;" title="<?php echo L('share');?>"><?php echo L('share');?></a>
                </li>
                <li class="J_down_menu_box mb_info pos_r">
                    <a href="<?php echo U('space/index', array('uid'=>$visitor['id']));?>" class="mb_name">
                        <img class="mb_avt r3" src="<?php echo avatar($visitor['id'], 24);?>">
                        <?php echo ($visitor["username"]); ?>
                    </a>
                    <ul class="J_down_menu s_m pos_a">
                        <li><a href="<?php echo U('space/index');?>"><?php echo L('cover');?></a></li>
                        <li><a href="<?php echo U('user/index');?>"><?php echo L('personal_settings');?></a></li>
                        <li><a href="<?php echo U('user/bind');?>"><?php echo L('user_bind');?></a></li>
                        <li><a href="<?php echo U('user/logout');?>"><?php echo L('logout');?></a></li>
                    </ul>
                </li>
                <li><a class="libg feed" href="<?php echo U('space/me');?>"><?php echo L('feed');?></a></li>
                <li><a class="libg album" href="<?php echo U('space/album');?>"><?php echo L('album');?></a></li>
                <li><a class="libg like" href="<?php echo U('space/like');?>"><?php echo L('like');?></a></li>
                <li class="J_down_menu_box my_shotcuts pos_r">
                    <a class="libg msg" href="javascript:;"><?php echo L('message');?><span id="J_msgtip"></span></a>
                    <ul class="J_down_menu s_m n_m pos_a">
                        <li><a href="<?php echo U('space/atme');?>"><?php echo L('talk');?><span id="J_atme"></span></a></li>
                        <li><a href="<?php echo U('message/index');?>"><?php echo L('my_msg');?><span id="J_msg"></span></a></li>
                        <li><a href="<?php echo U('message/system');?>"><?php echo L('system_msg');?><span id="J_system"></span></a></li>
                        <li><a href="<?php echo U('space/fans');?>"><?php echo L('my_fans');?><span id="J_fans"></span></a></li>
                    </ul>
                </li>
            </ul>
            <?php else: ?>
            <ul class="login_mod">
                <li class="local fl">
                    <a href="<?php echo U('user/register');?>"><?php echo L('register');?></a>
                    <a href="<?php echo U('user/login');?>"><?php echo L('login');?></a>
                </li>
                <li class="other_login fl">
                    <?php if(is_array($oauth_list)): $i = 0; $__LIST__ = $oauth_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><a href="<?php echo U('oauth/index', array('mod'=>$val['code']));?>" class="login_bg weibo_login"><img src="__STATIC__/images/oauth/<?php echo ($val["code"]); ?>/icon.png" /><?php echo ($val["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                </li>
            </ul><?php endif; ?>
        </div>
    </div>
    <div id="J_m_nav" class="clearfix">
        <ul class="nav_list fl">
            <li <?php if($nav_curr == 'index'): ?>class="current"<?php endif; ?>><a href="__ROOT__/"><?php echo L('index_page');?></a></li>

            <pin:nav type="lists" style="main">
            <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li class="split <?php if($nav_curr == $val['alias']): ?>current<?php endif; ?>"><a href="<?php echo ($val["link"]); ?>" <?php if($val["target"] == 1): ?>target="_blank"<?php endif; ?>><?php echo ($val["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            <li class="top_search">
                <form action="<?php echo U('book/search');?>" method="get" target="_blank">
                <input type="hidden" name="m" value="search">
                <input type="text" autocomplete="off" def-val="<?php echo C('pin_default_keyword');?>" value="<?php echo C('pin_default_keyword');?>" class="ts_txt fl" name="q">
                <input type="submit" class="ts_btn" value="<?php echo L('search');?>">
                </form>
            </li>
        </ul>
    </div>
</div-->


        <div class="content">
                        <div class="userbar">
                <div class="card">
                    <div class="avatar">
                        <a href="<?php echo U('space/index', array('uid'=>$user['id']));?>" title="<?php echo ($user["username"]); ?>"><img src="<?php echo avatar($user['id'], 200);?>" alt="<?php echo ($user["username"]); ?>" width="100" height="100" /></a>
                    </div>
                    <div class="username">
                        <h2><?php echo ($user["username"]); ?></h2>
                        <?php if($user['id'] == $visitor['id']): ?><a href="<?php echo U('user/index');?>">编辑我的资料</a>
                            <?php else: ?>
                            <a href="<?php echo U('space/info', array('uid'=>$user['id']));?>"><?php echo L('show_more');?>...</a><?php endif; ?>
                    </div>
                </div>
                <div class="card">
                    <div class="hot_left">
                        <h1>258041</h1>
                        <p>热度</p>
                    </div>
                    <div class="hot_right">
                        <table>
                            <tr>
                                <th>浏览</th>
                                <td>125841</td>
                            </tr>
                            <tr>
                                <th>喜欢</th>
                                <td>125841</td>
                            </tr>
                            <tr>
                                <th>收藏</th>
                                <td>125841</td>
                            </tr>
                            <tr>
                                <th>评论</th>
                                <td>125841</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="card longcard">
                    <?php if(!empty($album['intro'])): ?><div class="album_intro"><?php echo ($user["intro"]); ?></div><?php endif; ?>
                </div>
                <div class="clear"></div>
            </div>


            <div class="cbar clearfix">
                <a href="<?php echo U('space/index', array('uid'=>$user['id']));?>" class="current">图片</a>
                <a href="<?php echo U('space/album', array('uid'=>$user['id']));?>">专辑</a>
                <a href="<?php echo U('space/like', array('uid'=>$user['id']));?>">收藏</a>
                <a href="<?php echo U('space/follow', array('uid'=>$user['id']));?>">关注</a>
                <a href="<?php echo U('space/fans', array('uid'=>$user['id']));?>">关注我</a>
            </div>
            <div class="piccont clearfix">
                <div id="pic" class="clearfix" style="display:">
                    <div id="J_waterfall" class="clearfix" data-uri="__ROOT__/?m=space&a=item&uid=<?php echo ($user["id"]); ?>&sort=new">
                        <?php if(is_array($item_list)): $i = 0; $__LIST__ = $item_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?><div class="picitem">
        <div class="picbox">
            <a href="<?php echo U('item/index', array('id'=>$item['id']));?>" title="<?php echo ($item["title"]); ?>" target="_blank"><img alt="<?php echo ($item["title"]); ?>" src="./data/thumb/_thumb_280x_<?php echo ($item["uid"]); ?>_<?php echo ($item["id"]); ?>.jpg" width="280" height="280" /></a>
            <p>
                <a href="<?php echo U('item/index', array('id'=>$item['id']));?>" class="pictitle"><?php echo ($item["intro"]); ?></a>
                <b class="grade"><?php echo ($item["price"]); ?></b>
            </p>
        </div>
    </div><?php endforeach; endif; else: echo "" ;endif; ?>

                    </div>
                    <?php if(isset($show_load)): ?><div id="J_wall_loading" class="wall_loading tc gray"><span><?php echo L('loading');?></span></div><?php endif; ?>

                    <?php if(isset($page_bar)): ?><div id="J_wall_page" class="wall_page">
                            <div class="cover_more pt20"><a href="<?php echo U('space/item', array('uid'=>$user['id']));?>"><?php echo L('show_more');?>...</a></div>
                        </div><?php endif; ?>
                </div>

            </div>
        </div>
        <div class="footer"></div>
    </div>

<!-- unity footer -->
<div class="clear"></div>
<div id="footercontainer">
	<div id="footer" class="clearfix">
        <div class="fle">&copy;Copyright 2013 <a href="__ROOT__/" class="tdu clr6" target="_blank"><?php echo C('pin_site_name');?></a> (<a href="http://www.miibeian.gov.cn" class="tdu clr6" target="_blank"><?php echo C('pin_site_icp');?></a>)<?php echo C('pin_statistics_code');?>
        </div>
        <ul class="navi">
            <!--导航-->
            <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><li><a href="<?php echo ($val["link"]); ?>" <?php if($val["target"] == 1): ?>target="_blank"<?php endif; ?>><?php echo ($val["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            <!--关于-->
      		<li><a href="<?php echo U('aboutus/index', array('id'=>$val['id']));?>" target="_blank">关于我们</a></li>
            <!--友情链接-->
            <li><a href="<?php echo U('aboutus/flink');?>" target="_blank"><?php echo L('flink');?></a></li>
        </ul>
        <div class="clear"></div>
        <!--ul class="navi">
            <?php echo R('advert/index', array(8), 'Widget');?>
        </ul-->
		<div class="clear"></div>
	</div>
</div>
<!-- end unity footer -->
<div id="J_returntop" class="return_top"></div>

<script>
var PINER = {
    root: "__ROOT__",
    uid: "<?php echo $visitor['id'];?>", 
    async_sendmail: "<?php echo $async_sendmail;?>",
    config: {
        wall_distance: "<?php echo C('pin_wall_distance');?>",
        wall_spage_max: "<?php echo C('pin_wall_spage_max');?>"
    },
    //URL
    url: {}
};
//语言项目
var lang = {};
<?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?>
</script>
<?php $tag_load_class = new loadTag;$data = $tag_load_class->js(array('type'=>'js','href'=>'__STATIC__/js/jquery/plugins/jquery.tools.min.js,__STATIC__/js/jquery/plugins/jquery.masonry.js,__STATIC__/js/jquery/plugins/formvalidator.js,__STATIC__/js/fileuploader.js,__STATIC__/js/pinphp.js,__STATIC__/js/front.js,__STATIC__/js/dialog.js,__STATIC__/js/wall.js,__STATIC__/js/item.js,__STATIC__/js/user.js,__STATIC__/js/album.js','cache'=>'0','return'=>'data',));?>

</body>
</html>