<?php

use Models\M_UserToken;

require '../../application.php';
require '../../loader-api.php';

$allowed_image_types = array(
    'image/pjpeg',
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/x-png',
    'image/gif'
);
$upload_dir = "../../upload_avatar/temp/";
$img_dir = "../../upload_avatar/bg_img/";
$img_ori_dir = "../../upload_avatar/bg_img/ori/";
$allowed_image_ext = array_unique($allowed_image_types);
$action = request_action();
if ($action == 1) {
    if (isset($_FILES["backgroundImageFileToUpload"])) {
        $userfile_name = $_FILES['backgroundImageFileToUpload']['name'];
        $userfile_tmp = $_FILES['backgroundImageFileToUpload']['tmp_name'];
        $userfile_size = $_FILES['backgroundImageFileToUpload']['size'];
        $userfile_type = $_FILES['backgroundImageFileToUpload']['type'];
        $filename = basename($_FILES['backgroundImageFileToUpload']['name']);
        $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
        if ((!empty($_FILES["backgroundImageFileToUpload"])) && ($_FILES['backgroundImageFileToUpload']['error'] == 0)) {
            if (!in_array($userfile_type, $allowed_image_types)) {
                echo_result(array('code' => USER_ERROR, 'msg' => '只允许上传 JPG PNG GIF 格式图片~'));
            }
        } else {
            echo_result(array('code' => USER_ERROR, 'msg' => '请先选择图片在上传~'));
        }
        if (isset($_FILES['backgroundImageFileToUpload']['name'])) {
            $userid = request_userid();
            if (isset($userid)) {
                $employees = get_employees();
                $employee = $employees[$userid];
                $f_name = $employee['employee_no'] . '_' . (microtime(true) * 10000) . "." . $file_ext;
                $large_image_location = $upload_dir . $f_name;
                $res = move_uploaded_file($userfile_tmp, $large_image_location);
                if (!$res) {
                    echo_result(array('code' => USER_ERROR, 'msg' => '文件上传失败,请稍后重试~'));
                }
                $md5_name = md5_file($large_image_location);
                $new_file_path = $img_ori_dir . $md5_name . "." . $file_ext;
                $result = rename($large_image_location, $new_file_path);
                if (!$result) {
                    echo_result(array('code' => USER_ERROR, 'msg' => '文件上传失败,请稍后重试~'));
                }
                echo_result(array('code' => 0, 'dir' => $new_file_path));
//                $thumb_image_name = $img_dir . $md5_name . "." . $file_ext;
//                $thumbResult = createThumbnail($new_file_path, 180, 100, $thumb_image_name);
//                if ($thumbResult) {
//                    echo_result(array('code' => 0, 'dir' => $thumb_image_name));
//                } else {
//                    echo_result(array('code' => USER_ERROR, 'msg' => '生成缩略图失败,请稍后重试~'));
//                }
            } else {
                echo_result(array('code' => USER_ERROR, 'msg' => '获取用户信息失败,请稍后重试~'));
            }
        }
    }
}

/**
 * 生成保持原图纵横比的缩略图，支持.png .jpg .gif
 * 缩略图类型统一为.png格式
 * $srcFile     原图像文件名称
 * $toW         缩略图宽
 * $toH         缩略图高
 * $toFile      缩略图文件名称，为空覆盖原图像文件
 * @return bool    
 */
function createThumbnail($srcFile, $toW, $toH, $toFile = "") {
    if ($toFile == "") {
        $toFile = $srcFile;
    }
    $info = "";
    //返回含有4个单元的数组，0-宽，1-高，2-图像类型，3-宽高的文本描述。
    //失败返回false并产生警告。
    $data = getimagesize($srcFile, $info);
    if (!$data) return false;
    //将文件载入到资源变量im中
    switch ($data[2]) { //1-GIF，2-JPG，3-PNG
        case 1:
            if (!function_exists("imagecreatefromgif")) {
                echo "the GD can't support .gif, please use .jpeg or .png! <a href='javascript:history.back();'>back</a>";
                exit();
            }
            $im = imagecreatefromgif($srcFile);
            break;
        case 2:
            if (!function_exists("imagecreatefromjpeg")) {
                echo "the GD can't support .jpeg, please use other picture! <a href='javascript:history.back();'>back</a>";
                exit();
            }
            $im = imagecreatefromjpeg($srcFile);
            break;
        case 3:
            $im = imagecreatefrompng($srcFile);
            break;
    }
    //计算缩略图的宽高
    $srcW = imagesx($im);
    $srcH = imagesy($im);
    $toWH = $toW / $toH;
    $srcWH = $srcW / $srcH;
    if ($toWH <= $srcWH) {
        $ftoW = $toW;
        $ftoH = (int) ($ftoW * ($srcH / $srcW));
    } else {
        $ftoH = $toH;
        $ftoW = (int) ($ftoH * ($srcW / $srcH));
    }
    if (function_exists("imagecreatetruecolor")) {
        $ni = imagecreatetruecolor($ftoW, $ftoH); //新建一个真彩色图像
        if ($ni) {
            //重采样拷贝部分图像并调整大小 可保持较好的清晰度
            imagecopyresampled($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
        } else {
            //拷贝部分图像并调整大小
            $ni = imagecreate($ftoW, $ftoH);
            imagecopyresized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
        }
    } else {
        $ni = imagecreate($ftoW, $ftoH);
        imagecopyresized($ni, $im, 0, 0, 0, 0, $ftoW, $ftoH, $srcW, $srcH);
    }
    //保存到文件 统一为.png格式
    imagepng($ni, $toFile); //以 PNG 格式将图像输出到浏览器或文件
    ImageDestroy($ni);
    ImageDestroy($im);
    return true;
}
