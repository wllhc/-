<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/html">
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
    <script type="text/javascript" src="__STATIC__/js/jquery/jquery.js"></script>
    <script type="text/javascript" src="__STATIC__/js/jquery/jquery.exif.js"></script>
    <script type="text/javascript" src="__STATIC__/js/global.js"></script>
    <script type="text/javascript" src="__STATIC__/js/photo_upload.js"></script>
    <script type="text/javascript" src="__STATIC__/js/ImageCopper.js"></script>
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

    <form id="img_form" action="<?php echo U('upload/add_data');?>" method="post">
        <div class="content">
            <div class="upload" id="upload">
                <div class="uploadpic" id="up_preview">
                    <img id="imghead" style="display:none;" src=""/>

                    <h1 id="uptext" class="uptext" style="display:;">
                        点击<a href="#" id="imginput">这里<input type="button" class="upfile"/></a>从您的电脑里上传一张图片
                    </h1>

                    <h1 class="upbar" id="upbar" style="display:none;"><b id="done" style="width:0%"></b><span
                            id="file_info">0%</span></h1>
                </div>

                <div class="upbtnbar">
                    <div class="up_preview">
                        <img src="temp/uppreview.jpg" style="display:;"/>
                    </div>
                    <div class="up_btn">
                        <input type="button" class="upbtn_del" id="upbtn_del" onClick="gUpload.delImg();"
                               style="display:none;" value="删 除"/>
                        <input type="button" class="upbtn_ok" onclick="document.getElementById('img_form').submit();"
                               value="完成上传"/>
                    </div>
                </div>
            </div>

            <div class="uploading" id="uploading" style="display:none;">
                <div class="ulingo" id="upload_bar" style="width:0%;"></div>
                <b id="upload_process">0%</b>
            </div>


            <div class="picinfo">
                <div class="info_t">
                    <div class="info_t_l">
                        <div class="info_t_lt">
                            <h3 class="infot">图片标题</h3>
                            <input type="text" id="FileName" name="title" class="pictit_text" placeholder="设置标题"/>
                        </div>
                        <div class="info_t_lr">
                            <h3 class="infot">价格</h3>
                            <input type="text" id="price" name="price" class="pictit_text prace_text"
                                   placeholder="设置价格( 建议>300 )"/>
                            <b>￥</b>
                        </div>
                        <input type="hidden" id='img_name' name='img' value="">
                        <input type="hidden" id='img_path' name='save_path' value="">
                        <input type="hidden" id='save_name' name='save_name' value="">
                        <!--切图所用的隐藏表单-->
                        <input type="hidden" id='x' name='x' value="">
                        <input type="hidden" id='y' name='y' value="">
                        <input type="hidden" id='w' name='w' value="">
                        <input type="hidden" id='h' name='h' value="">
                        <!--百度地图坐标标记隐藏表单-->
                        <input type="hidden" id='lng' name='lng' value="">
                        <input type="hidden" id='lat' name='lat' value="">


                    </div>
                    <div class="info_t_r">
                        <h3 class="infot">信息完整度</h3>

                        <p>填写更多的信息可提高图片被发现的几率</p>

                        <div class="infobar"><b style="width:50%"></b></div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="infocont">
                    <div class="picinfo_left">
                        <h3 class="infot">EXIF信息</h3>
                        <table class="tabexif">
                            <tr>
                                <th>相机</th>
                                <td><input type="text" id="Model" name="Model" class="exiftext" value=''
                                           placeholder="填写相机"/></td>
                            </tr>
                            <tr>
                                <th>镜头</th>
                                <td><input type="text" class="exiftext" placeholder="填写镜头"/></td>
                            </tr>
                            <tr>
                                <th>焦距</th>
                                <td><input type="text" id="FocalLength" name="FocalLength" class="exiftext"
                                           placeholder="填写焦距"/></td>
                            </tr>
                            <tr>
                                <th>快门速度</th>
                                <td><input type="text" id="ShutterSpeedValue" name="ShutterSpeedValue" class="exiftext"
                                           placeholder="填写快门速度"/></td>
                            </tr>
                            <tr>
                                <th>光圈</th>
                                <td><input type="text" id="ApertureValue" name="ApertureValue" class="exiftext"
                                           placeholder="填写光圈"/></td>
                            </tr>
                            <tr>
                                <th>ISO</th>
                                <td><input type="text" id="ISOSpeedRatings" name="ISOSpeedRatings" class="exiftext"
                                           placeholder="填写ISO"/></td>
                            </tr>
                            <tr>
                                <th>拍摄时间</th>
                                <td><input type="text" id="DateTimeOriginal" name="DateTimeOriginal" class="exiftext"
                                           placeholder="填写拍摄时间"/></td>
                            </tr>
                        </table>
                        <h3 class="infot">照片分类</h3>

                        <div class="selbox">
                            <select class="infoselect" name="cate_id" style="width:310px;">
                                <?php if(is_array($item_cate)): $i = 0; $__LIST__ = $item_cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cate): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cate["id"]); ?>"><?php echo ($cate["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                            <span></span>
                        </div>
                        <h3 class="infot">图片属于</h3>

                        <div class="selbox">
                            <select class="infoselect" style="width:310px;">
                                <option>公共</option>
                                <option>私人</option>
                            </select>
                            <span></span>
                        </div>
                    </div>
                    <div class="picinfo_center">
                        <h3 class="infot">图片描述</h3>
                        <textarea class="infodec" name="intro" placeholder="请填写图片描述"></textarea>

                        <h3 class="infot">许可证</h3>

                        <div class="selbox">
                            <select class="infoselect" style="width:397px;">

                                <option></option>

                            </select>
                            <span></span>
                        </div>
                        <h3 class="infot">标签</h3>
                        <input type="text" class="tagtext" placeholder="填写标签以增加图片被发现的几率，多个用”，“分割"/>
                    </div>
                    <div class="picinfo_right">
                        <h3 class="infot">拍摄地点</h3>
                        <div style="height:300px" id="bMap">

                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </form>
    <div class="footer"></div>
</div>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=1.4"></script>
<script>
    var bmap = new BMap.Map("bMap");
    bmap.centerAndZoom(new  BMap.Point(116.404,  39.915), 12);
    bmap.enableScrollWheelZoom(); //允许鼠标滚轮滑动放大缩小地图
    var icon = new BMap.Icon('__STATIC__/images/point.png', new BMap.Size(18, 30), {
        anchor: new BMap.Size(9, 30)
    });

    var mkr = new BMap.Marker(new BMap.Point(116.38075,39.918986), {
        icon: icon,
        enableDragging: true,
        raiseOnDrag: true
    });
    bmap.addOverlay(mkr);
    mkr.addEventListener('dragend', function(e){
        g('lng').value= e.point.lng;
        g('lat').value= e.point.lat;
    })






    /**
     * upload页面js类
     *
     * @desc modified by lpf. 把之前的js代码进行了对象化修改。
     */
    function Upload() {
        var __this = this;

        this.init = function () {
            __this.initUpload();
        }

        /**
         * “删除”按钮的事件
         *
         */
        this.delImg = function () {
            if ("" == g("upbtn_del").style.display) {
                g('imghead').src = "temp/uppreview.jpg";
                g("imghead").style.display = "none";
                g('upbar').style.display = "";
            }
            return;
        };


        /**
         * 向表单中添加内容
         *
         * @param info
         */
        this.info_input = function (info) {
            var _fileName = info.data.FileName ? info.data.FileName : info.data.name;
            var _extension = info.data.extension;// 图片扩展名

            $('#Model').val(info.data.Model);
            $('#FocalLength').val(info.data.FocalLength);
            $('#ShutterSpeedValue').val(info.data.ShutterSpeedValue);
            $('#ApertureValue').val(info.data.ApertureValue);
            $('#ISOSpeedRatings').val(info.data.ISOSpeedRatings);
            $('#DateTimeOriginal').val(info.data.DateTimeOriginal);
            $('#FileName').val(_fileName);
            $('#img_name').val(_fileName);
            $('#img_path').val(info.data.savepath + info.data.savename);
        }

        /**
         * 上传方法
         *
         */
        this.initUpload = function () {
            new EmUpload({
                url: "<?php echo U('upload/upajax');?>",
                url2: "<?php echo U('upload/upstatus');?>",
                place: 'imginput',
                onStart: function (key, file) {
                    g("upload").style.display = "none";
                    g("uploading").style.display = "";
                    //g('upbar').style.display = "block";
                    g('uptext').style.display = "none";
                    //g('file_info').innerHTML = file;             
                },
                onProgress: function (key, file, total, current, speed) {
                    // g('speed').innerHTML = '(' + formatBytes(speed) + '/秒)'
                    g('upload_process').innerHTML = Math.ceil(current / total * 100) + '%';
                    g('upload_bar').style.width = Math.ceil(current / total * 100) + '%';
                },
                onDone: function (key, file, file_info) {
                    __this.info_input(file_info);

                    g('upload_process').innerHTML = file;

                    var h = file_info.data.ExifImageLength;
                    var w = file_info.data.ExifImageWidth;
                    var _per = 1180 / 580;
                    var scale = 1;
                    if ((w / h) > _per) { // 以宽度为准
                        g('imghead').width = 1180;
                        scale = w / 1180;
                    } else {
                        g('imghead').height = 580;
                        scale = h / 580;
                    }

                    g('imghead').src = file_info.data.savepath + file_info.data.savename;
                    g('imghead').onload = function () {
                        new ImageCopper(this, {LockRate: true, Left: 250, Top: 30,Scale: scale.toFixed(2)}, function (x, y, w, h) {
                            g('x').value = (x*scale).toFixed(0);
                            g('y').value = (y*scale).toFixed(0);
                            g('w').value = (w*scale).toFixed(0);
                            g('h').value = (h*scale).toFixed(0);
                        })
                    };
                    g("imghead").style.display = "";
                    g("upbtn_del").style.display = "";
                    g('upbar').style.display = "none";
                    g("upload").style.display = "";
                    g("uploading").style.display = "none";
                    //g('speed').innerHTML = '(0 B/秒)'
                    //g('text').innerHTML = 
                    g('upload_bar').style.width = '100%';
                },
                onError: function (key, file, error) {
                    // g('file_info').innerHTML = '';             
                    // g('speed').innerHTML = '(0 B/秒)'
                    //g('text').innerHTML = g('done').style.width =  '100%';
                    fMessage.show(error, "fail");
                }
            });

        }

        this.previewImage = function (file) {
            var MAXWIDTH = 100;
            var MAXHEIGHT = 100;
            //var div = document.getElementById('up_preview');
            if (file.files && file.files[0]) {
                //div.innerHTML = '<img id=imghead>';
                var img = document.getElementById('imghead');
                /*img.onload = function(){
                 var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                 img.width = rect.width;
                 img.height = rect.height;
                 img.style.marginLeft = rect.left+'px';
                 img.style.marginTop = rect.top+'px';
                 }*/
                var reader = new FileReader();
                reader.onload = function (evt) {
                    img.src = evt.target.result;
                }
                reader.readAsDataURL(file.files[0]);
            } else {
                var sFilter = 'filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
                file.select();
                var src = document.selection.createRange().text;
                div.innerHTML = '<img id="imghead" />';
                var img = document.getElementById('imghead');
                img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
                var rect = __this.clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                status = ('rect:' + rect.top + ',' + rect.left + ',' + rect.width + ',' + rect.height);
                div.innerHTML = "<div id=divhead style='width:" + rect.width + "px;height:" + rect.height + "px;margin-top:" + rect.top + "px;margin-left:" + rect.left + "px;" + sFilter + src + "\"'></div>";
            }
        }

        this.clacImgZoomParam = function (maxWidth, maxHeight, width, height) {
            var param = {top: 0, left: 0, width: width, height: height};
            if (width > maxWidth || height > maxHeight) {
                rateWidth = width / maxWidth;
                rateHeight = height / maxHeight;

                if (rateWidth > rateHeight) {
                    param.width = maxWidth;
                    param.height = Math.round(height / rateWidth);
                } else {
                    param.width = Math.round(width / rateHeight);
                    param.height = maxHeight;
                }
            }

            param.left = Math.round((maxWidth - param.width) / 2);
            param.top = Math.round((maxHeight - param.height) / 2);
            return param;
        }
    }

    gUpload = new Upload();
    gUpload.init();

</script>
</body>
</html>