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
$max_file = "1";
$max_width = 500;
$max_height = 420;
$thumb_size = 100;
$upload_dir = "../../upload_avatar/temp/";
$avatar_dir = "../../upload_avatar/avatar/";
$talk_dir = "../../upload_avatar/talk/";
$talk_ori_dir = "../../upload_avatar/talk/ori/";
$allowed_image_ext = array_unique($allowed_image_types);
$action = request_action();
if ($action == 1) {
    if (isset($_FILES["avatarFileToUpload"])) {
        $userfile_name = $_FILES['avatarFileToUpload']['name'];
        $userfile_tmp = $_FILES['avatarFileToUpload']['tmp_name'];
        $userfile_size = $_FILES['avatarFileToUpload']['size'];
        $userfile_type = $_FILES['avatarFileToUpload']['type'];
        $filename = basename($_FILES['avatarFileToUpload']['name']);
        $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
        if ((!empty($_FILES["avatarFileToUpload"])) && ($_FILES['avatarFileToUpload']['error'] == 0)) {
            if (!in_array($userfile_type, $allowed_image_types)) {
                echo_result(array('code' => USER_ERROR, 'msg' => '只允许上传 JPG PNG GIF 格式图片~'));
            }
            if ($userfile_size > ($max_file * 1048576)) {
                echo_result(array('code' => USER_ERROR, 'msg' => '上传图片不能大于' . $max_file . 'MB~'));
            }
        } else {
            echo_result(array('code' => USER_ERROR, 'msg' => '请先选择图片在上传~'));
        }
        if (isset($_FILES['avatarFileToUpload']['name'])) {
            $userid = request_userid();
            if (isset($userid)) {
                $employees = get_employees();
                $employee = $employees[$userid];
                $f_name = $employee['employee_no'] . '_' . (microtime(true) * 10000) . "." . $file_ext;
                $large_image_location = $upload_dir . $f_name;
                move_uploaded_file($userfile_tmp, $large_image_location);
                chmod($large_image_location, 0777);
                $width = getWidth($large_image_location);
                $height = getHeight($large_image_location);
                if ($width > $max_width) {
                    if (is_file($large_image_location)) {
                        unlink($large_image_location);
                    }
                    echo_result(array('code' => USER_ERROR, 'msg' => '上传图片宽度不能大于' . $max_width . 'px~'));
                }
                if ($height > $max_height) {
                    if (is_file($large_image_location)) {
                        unlink($large_image_location);
                    }
                    echo_result(array('code' => USER_ERROR, 'msg' => '上传图片高度不能大于' . $max_height . 'px~'));
                }
                echo_result(array('code' => 0, 'dir' => $f_name));
            } else {
                echo_result(array('code' => USER_ERROR, 'msg' => '获取用户信息失败,请稍后重试~'));
            }
        }
    }
}
if ($action == 2) {
    $dataObject = request_object();
    $x1 = $dataObject->x;
    $y1 = $dataObject->y;
    $x2 = $dataObject->x2;
    $y2 = $dataObject->y2;
    $w = $dataObject->w;
    $h = $dataObject->h;
    $path = $avatar_dir . $dataObject->name;
    $old_path = $upload_dir . $dataObject->name;
    $scale = $thumb_size / $w;
    $cropped = resizeThumbnailImage($path, $old_path, $w, $h, $x1, $y1, $scale);
    $file_ext = strtolower(substr($path, strrpos($path, '.') + 1));
    if (!empty($cropped)) {
        if (is_file($old_path)) {
            unlink($old_path);
        }
        $userid = 0;
        if (isset($dataObject->userid)) {
            $userid = $dataObject->userid;
        } else {
            $userid = request_login_userid();
        }
        $user_token = new M_UserToken($userid);
        $db = create_pdo();
        $result = $user_token->load($db, $user_token);
        if ($result[0]) {
            $avatar = $user_token->get_avatar();
            if (isset($avatar)) {
                if (is_file($avatar)) {
                    unlink($avatar);
                }
            }
        }
        $user_token->set_avatar($cropped);
        $user_token->update($db);
        set_cookie($db, $user_token);
        echo_result(array('code' => 0, 'avatar' => $cropped, 'msg' => '裁剪头像成功~'));
    } else {
        echo_result(array('code' => USER_ERROR, 'msg' => '裁剪头像失败,请稍后重试~'));
    }
}
if ($action == 3) {
    if (isset($_FILES['imageUpload'])) {
        $userfile_name = $_FILES['imageUpload']['name'];
        $userfile_tmp = $_FILES['imageUpload']['tmp_name'];
        $userfile_size = $_FILES['imageUpload']['size'];
        $userfile_type = $_FILES['imageUpload']['type'];
        $filename = basename($_FILES['imageUpload']['name']);
        $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
        if (str_equals($file_ext, 'lob')) {
            $file_ext = "png";
        }
        if ((!empty($_FILES["imageUpload"])) && ($_FILES['imageUpload']['error'] == 0)) {
            if (!in_array($userfile_type, $allowed_image_types)) {
                echo_result(array('code' => USER_ERROR, 'msg' => '只允许上传 JPG PNG GIF 格式图片~'));
            }
//            if ($userfile_size > ($max_file * 1048576)) {
//                echo_result(array('code' => USER_ERROR, 'msg' => '上传图片不能大于' . $max_file . 'MB~'));
//            }
        } else {
            echo_result(array('code' => USER_ERROR, 'msg' => '请先选择图片在上传~'));
        }
        if (isset($_FILES['imageUpload']['name'])) {
            $f_name = (microtime(true) * 10000) . "." . $file_ext;
            $large_image_location = $upload_dir . $f_name;
            move_uploaded_file($userfile_tmp, $large_image_location);
            $md5 = md5_file($large_image_location);
            $newPath = $talk_dir . $md5 . "." . $file_ext;
            rename($large_image_location, $newPath);
            $width = getWidth($newPath);
            $height = getHeight($newPath);
            if ($width > 560) {
                $newPath_ori = $talk_ori_dir . "O_" . $md5 . "." . $file_ext;
                copy($newPath, $newPath_ori);
                $newPath_ = $talk_dir . "O_" . $md5 . "." . $file_ext;
                rename($newPath, $newPath_);
                $newPath = $newPath_;
                $scale = 560 / $width;
                resizeThumbnailImage($newPath, $newPath, $width, $height, 0, 0, $scale);
            } else if ($width < 560 && $width > 280) {
                $scale = 276 / $width;
                resizeThumbnailImage($newPath, $newPath, $width, $height, 0, 0, $scale);
            } else if ($width < 280 && $width > 180) {
                $scale = 182 / $width;
                resizeThumbnailImage($newPath, $newPath, $width, $height, 0, 0, $scale);
            } else if ($width < 180 && $width > 100) {
                $scale = 136 / $width;
                resizeThumbnailImage($newPath, $newPath, $width, $height, 0, 0, $scale);
            }
            echo_result(array('code' => 0, 'dir' => $newPath));
        } else {
            echo_result(array('code' => USER_ERROR, 'msg' => '上传失败,请稍后重试~'));
        }
    } else {
        echo_result(array('code' => USER_ERROR, 'msg' => '上传失败,请稍后重试~'));
    }
}

function getHeight($image) {
    $size = getimagesize($image);
    $height = $size[1];
    return $height;
}

function getWidth($image) {
    $size = getimagesize($image);
    $width = $size[0];
    return $width;
}

function resizeImage($image, $width, $height, $scale) {
    list($imagewidth, $imageheight, $imageType) = getimagesize($image);
    $imageType = image_type_to_mime_type($imageType);
    $newImageWidth = ceil($width * $scale);
    $newImageHeight = ceil($height * $scale);
    $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
    switch ($imageType) {
        case "image/gif":
            $source = imagecreatefromgif($image);
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            $source = imagecreatefromjpeg($image);
            break;
        case "image/png":
        case "image/x-png":
            $source = imagecreatefrompng($image);
            break;
    }
    imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);
    switch ($imageType) {
        case "image/gif":
            imagegif($newImage, $image);
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            imagejpeg($newImage, $image, 90);
            break;
        case "image/png":
        case "image/x-png":
            imagepng($newImage, $image);
            break;
    }
    chmod($image, 0777);
    return $image;
}

function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale) {
    list($imagewidth, $imageheight, $imageType) = getimagesize($image);
    $imageType = image_type_to_mime_type($imageType);
    $newImageWidth = ceil($width * $scale);
    $newImageHeight = ceil($height * $scale);
    $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
    switch ($imageType) {
        case "image/gif":
            $source = imagecreatefromgif($image);

            $color = imagecolorallocate($newImage, 255, 255, 255);
            imagecolortransparent($newImage, $color);
            imagefill($newImage, 0, 0, $color);
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            $source = imagecreatefromjpeg($image);
            break;
        case "image/png":
        case "image/x-png":
            $source = imagecreatefrompng($image);
            imagesavealpha($source, true);
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            break;
    }
    imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
    switch ($imageType) {
        case "image/gif":
            imagegif($newImage, $thumb_image_name);
            break;
        case "image/pjpeg":
        case "image/jpeg":
        case "image/jpg":
            imagejpeg($newImage, $thumb_image_name, 90);
            break;
        case "image/png":
        case "image/x-png":
            imagepng($newImage, $thumb_image_name);
            break;
    }
    chmod($thumb_image_name, 0777);
    return $thumb_image_name;
}
