<?php

class uploadAction extends userbaseAction
{
    /**
     * 缩略图后缀大小尺寸
     * @var array
     */
    protected $__thumb_size = array(
        'small' => '_thumb_280x_',
        'big' => '_thumb_900x_',
    );

    /**
     * 缓存保存的路径
     * @var string
     */
    protected $__save_path = './data/upload/photo/';

    /**
     * 缩略图保存路径
     * @var string
     */
    protected $__thumb_path = './data/thumb/';

    /**
     * 上传页面方法和数据初始化
     *
     */
    public function index()
    {
        $item_cate = M('item_cate');
        $cate_list = $item_cate->where('status=1')->select();
        $this->assign('item_cate', $cate_list);
        $this->_config_seo(array(
            'title' => L('添加新照片'),
        ));
        $this->display();
    }

    /**
     * ajax文件上传，存入临时文件夹，add_data时才转移文件
     *
     * @param void
     * @return mixe exif信息
     */
    public function upajax()
    {
        import('ORG.Net.UploadFile');
        $upload = new UploadFile(); // 实例化上传类
        $upload->maxSize = 314572800; // 设置附件上传大小
        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
        $upload->savePath = $this->__save_path; // 设置附件上传目录
        $upload->autoSub = true; // 是否开启子目录存储
        $upload->subType = 'hash'; // 文件路径生成的方式
        $upload->thumb = true; // 是否生成缩略图
        // $upload->thumbFile = ;
        $upload->thumbMaxWidth = '280,900'; // 缩略图的宽
        $upload->thumbMaxHeight = '280,1000'; // 缩略图的高
        $upload->thumbPrefix = '';
        $upload->thumbSuffix = $this->__thumb_size['small'] . ',' . $this->__thumb_size['big']; // 缩略图的后缀
        if (!$upload->upload()) { // 上传错误提示错误信息
            $this->ajaxReturn($upload->getErrorMsg(), 'ERROR!', 0);

        } else { // 上传成功


            $file_info = $upload->getUploadFileInfo();
            $exif = $this->getImageExif($file_info[0]['savepath'] . $file_info[0]['savename']);
            //$exif = $this->getImageExif($_FILES['Filedata']['tmp_name']);

            $this->ajaxReturn(1, 'OK!', array_merge($exif, $file_info[0]));
        }
    }

    /**
     * 上传进度返回，apc扩展支持
     *
     */
    public function upstatus()
    {
        $key = $_REQUEST['key'];

        $status = apc_fetch('upload_' . $key);
        if (false !== $status) {
            list($usec, $sec) = explode(" ", microtime());
            $status['now'] = ((float)$usec + (float)$sec);
        }

        $is_done = $_REQUEST['is_done'];
        if ($is_done) {
            apc_delete('upload_' . $key);
            echo json_encode(array('clean' => 1));
        } else {

            echo json_encode($status);
        }
    }

    /**
     * 数据插入，同时生成小样和添加水印，源文件转移
     *
     */
    public function add_data()
    {
        $info = $this->_post();

        // 移动原图
        // 上传文件的临时路径
        $file_name = $info['save_path'];
        // 上传图片的临时路径和文件名
        // $file_name = $file_path . $info['img'];
        // 新的移动路径和文件名
        $new_name = './data/photos/' . get_upload_file_name($info['img']);

        // 移动成功则插入数据库
        if (rename($file_name, $new_name)) {
        } else {
            unlink($file_name);
            $this->error('上传失败[原图移动失败]', U('home/upload/index'));
        }
        $userinfo = session('user_info');
        $info['uid'] = $userinfo['id'];
        $info['uname'] = $userinfo['username'];

        // 插入数据库
        $info['img'] = $new_name;
        $item = D('item');
        $id = $item->publish($info);

        import('ORG.Net.Image');
        $mime = get_file_ext($info['img']);
        $thumb_old_name_1 = str_replace('.' . $mime, '', $file_name) . $this->__thumb_size['small'] . '.' . $mime;
        $thumb_old_name_2 = str_replace('.' . $mime, '', $file_name) . $this->__thumb_size['big'] . '.' . $mime;

        if ($id) {
            $thumb_new_name_1 = $this->__thumb_path . $this->__thumb_size['small'] . $info['uid'] . '_' . $id . '.' . $mime;
            $thumb_new_name_2 = $this->__thumb_path . $this->__thumb_size['big'] . $info['uid'] . '_' . $id . '.' . $mime;
            Image::cutImage($new_name, $thumb_new_name_1, '', $info['x'], $info['y'], $info['w'], $info['h'], 280, 280);
            if (rename($thumb_old_name_2, $thumb_new_name_2)) {
                $this->success('上传成功',U('home/user/index'));
                exit;
            } else {
                // 删除数据库中添加的数据
                $item->delete($id);
            }
        }
        // 上传失败 恢复上传前状态，删除上传失败的文件
        unlink($thumb_old_name_1);
        unlink($thumb_old_name_2);
        // 文件上传失败就删除移动之后的文件
        unlink($file_name);
        unlink($new_name);
        $this->error('上传失败[缩略图移动失败或图片数据写入失败]', U('home/upload/index'));
    }

    protected function GetImageInfoVal($ImageInfo, $val_arr)
    {
        $InfoVal = "未知";
        foreach ($val_arr as $name => $val) {
            if ($name == $ImageInfo) {
                $InfoVal = & $val;
                break;
            }
        }
        return $InfoVal;
    }

    /**
     * 获取图象信息的函数
     *
     * 一个全面获取图象信息的函数
     *
     * @access public
     * @param string $img 图片路径
     * @return array
     */
    private function getImageExif($img)
    {

        $imgtype = array("", "GIF", "JPG", "PNG", "SWF", "PSD", "BMP", "TIFF(intel byte order)", "TIFF(motorola byte order)", "JPC", "JP2", "JPX", "JB2", "SWC", "IFF", "WBMP", "XBM");
        $Orientation = array("", "top left side", "top right side", "bottom right side", "bottom left side", "left side top", "right side top", "right side bottom", "left side bottom");
        $ResolutionUnit = array("", "", "英寸", "厘米");
        $YCbCrPositioning = array("", "the center of pixel array", "the datum point");
        $ExposureProgram = array("未定义", "手动", "标准程序", "光圈先决", "快门先决", "景深先决", "运动模式", "肖像模式", "风景模式");
        $MeteringMode_arr = array(
            "0" => "未知",
            "1" => "平均",
            "2" => "中央重点平均测光",
            "3" => "点测",
            "4" => "分区",
            "5" => "评估",
            "6" => "局部",
            "255" => "其他"
        );
        $Lightsource_arr = array(
            "0" => "未知",
            "1" => "日光",
            "2" => "荧光灯",
            "3" => "钨丝灯",
            "10" => "闪光灯",
            "17" => "标准灯光A",
            "18" => "标准灯光B",
            "19" => "标准灯光C",
            "20" => "D55",
            "21" => "D65",
            "22" => "D75",
            "255" => "其他"
        );
        $Flash_arr = array(
            "0" => "flash did not fire",
            "1" => "flash fired",
            "5" => "flash fired but strobe return light not detected",
            "7" => "flash fired and strobe return light detected",
        );

        $exif = exif_read_data($img, "ANY_TAG");

        if ($exif === false) {
            $new_img_info = array("文件信息" => "没有图片EXIF信息");
        } else {
            $exif = exif_read_data($img, 0, true);
            $new_img_info = array(
                //"文件信息" => "-----------------------------",
                "FileName" => $exif['FILE']['FileName'],
                "FileType" => $imgtype[$exif['FILE']['FileType']],
                "MimeType" => $exif['FILE']['MimeType'],
                "FileSize" => $exif['FILE']['FileSize'],
                "FileDateTime" => date("Y-m-d H:i:s", $exif['FILE']['FileDateTime']),
                //"图像信息" => "-----------------------------",
                "ImageDescription" => $exif['IFD0']['ImageDescription'],
                "Make" => $exif['IFD0']['Make'],
                "Model" => $exif['IFD0']['Model'],
                "Orientation" => $Orientation[$exif['IFD0']['Orientation']],
                "XResolution" => $exif['IFD0']['XResolution'] . $ResolutionUnit[$exif['IFD0']['ResolutionUnit']],
                "YResolution" => $exif['IFD0']['YResolution'] . $ResolutionUnit[$exif['IFD0']['ResolutionUnit']],
                "ResolutionUnit" => $exif['IFD0']['Software'],
                "DateTime" => $exif['IFD0']['DateTime'],
                "Artist" => $exif['IFD0']['Artist'],
                "YCbCrPositioning" => $YCbCrPositioning[$exif['IFD0']['YCbCrPositioning']],
                "Copyright" => $exif['IFD0']['Copyright'],
                "Copyright_Photographer" => $exif['COMPUTED']['Copyright.Photographer'],
                "Copyright_Editor" => $exif['COMPUTED']['Copyright.Editor'],
                //"拍摄信息" => "-----------------------------",
                "ExifVersion" => $exif['EXIF']['ExifVersion'],
                "FlashPixVersion" => "Ver. " . number_format($exif['EXIF']['FlashPixVersion'] / 100, 2),
                "DateTimeOriginal" => $exif['EXIF']['DateTimeOriginal'],
                "DateTimeDigitized" => $exif['EXIF']['DateTimeDigitized'],
                "Height" => $exif['COMPUTED']['Height'],
                "Width" => $exif['COMPUTED']['Width'],
                /*
                  The actual aperture value of lens when the image was taken.
                  Unit is APEX.
                  To convert this value to ordinary F-number(F-stop),
                  calculate this value's power of root 2 (=1.4142).
                  For example, if the ApertureValue is '5', F-number is pow(1.41425,5) = F5.6.
                 */
                "ApertureValue" => $exif['EXIF']['ApertureValue'],
                "ShutterSpeedValue" => $exif['EXIF']['ShutterSpeedValue'],
                "ApertureFNumber" => $exif['COMPUTED']['ApertureFNumber'],
                "MaxApertureValue" => "F" . $exif['EXIF']['MaxApertureValue'],
                "ExposureTime" => $exif['EXIF']['ExposureTime'],
                "FNumber" => $exif['EXIF']['FNumber'],
                "MeteringMode" => $this->GetImageInfoVal($exif['EXIF']['MeteringMode '], $MeteringMode_arr),
                "LightSource" => $this->GetImageInfoVal($exif['EXIF']['LightSource '], $Lightsource_arr),
                "Flash" => $this->GetImageInfoVal($exif['EXIF']['Flash '], $Flash_arr),
                "ExposureMode" => ($exif['EXIF']['ExposureMode'] == 1 ? "手动" : "自动"),
                "WhiteBalance" => ($exif['EXIF']['WhiteBalance'] == 1 ? "手动" : "自动"),
                "ExposureProgram" => $ExposureProgram[$exif['EXIF']['ExposureProgram']],
                /*
                  Brightness of taken subject, unit is APEX. To calculate Exposure(Ev) from BrigtnessValue(Bv), you must add SensitivityValue(Sv).
                  Ev=Bv+Sv Sv=log((ISOSpeedRating/3.125),2)
                  ISO100:Sv=5, ISO200:Sv=6, ISO400:Sv=7, ISO125:Sv=5.32.
                 */
                "ExposureBiasValue" => $exif['EXIF']['ExposureBiasValue'] . "EV",
                "ISOSpeedRatings" => $exif['EXIF']['ISOSpeedRatings'],
                "ComponentsConfiguration" => (bin2hex($exif['EXIF']['ComponentsConfiguration']) == "01020300" ? "YCbCr" : "RGB"), //'0x04, 0x05, 0x06, 0x00'="RGB" '0x01, 0x02, 0x03, 0x00'="YCbCr"
                "CompressedBitsPerPixel" => $exif['EXIF']['CompressedBitsPerPixel'] . "Bits/Pixel",
                "FocusDistance" => $exif['COMPUTED']['FocusDistance'] . "m",
                "FocalLength" => $exif['EXIF']['FocalLength'] . "mm",
                "FocalLengthIn35mmFilm" => $exif['EXIF']['FocalLengthIn35mmFilm'] . "mm",
                /*
                  '0x41, 0x53, 0x43, 0x49, 0x49, 0x00, 0x00, 0x00':ASCII
                  '0x4a, 0x49, 0x53, 0x00, 0x00, 0x00, 0x00, 0x00':JIS
                  '0x55, 0x4e, 0x49, 0x43, 0x4f, 0x44, 0x45, 0x00':Unicode
                  '0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00':Undefined
                 */
                "UserCommentEncoding" => $exif['COMPUTED']['UserCommentEncoding'],
                "UserComment" => $exif['COMPUTED']['UserComment'],
                "ColorSpace" => ($exif['EXIF']['ColorSpace'] == 1 ? "sRGB" : "Uncalibrated"),
                "ExifImageLength" => $exif['EXIF']['ExifImageLength'],
                "ExifImageWidth" => $exif['EXIF']['ExifImageWidth'],
                "FileSource" => (bin2hex($exif['EXIF']['FileSource']) == 0x03 ? "digital still camera" : "unknown"),
                "SceneType" => (bin2hex($exif['EXIF']['SceneType']) == 0x01 ? "A directly photographed image" : "unknown"),
                "Thumbnail_FileType" => $exif['COMPUTED']['Thumbnail.FileType'],
                "Thumbnail_MimeType" => $exif['COMPUTED']['Thumbnail.MimeType']
            );
        }
        return $new_img_info;
    }

    /**
     * 移动上传成功的文件
     *
     */
    private function _mov_img()
    {

    }
}
