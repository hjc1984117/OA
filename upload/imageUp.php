<?php

use Common\UMEditor\Uploader;

require '../application.php';
require '../loader-api.php';

execute_request(HttpRequestMethod::Post, function() {
    //上传配置
    $config = array(
        "savePath" => "upload/", //存储文件夹
        "maxSize" => 1000, //允许的文件最大尺寸，单位KB
        "allowFiles" => array(".gif", ".png", ".jpg", ".jpeg", ".bmp")  //允许的文件格式
    );

    $fileName = md5(request_userid() . time() . rand(1, 10000)) . ".png";
    $up = new Uploader($fileName, "upfile", $config);
    $type = $_REQUEST['type'];
    $callback = $_GET['callback'];

    $info = $up->getFileInfo();
    /**
     * 返回数据
     */
    if ($callback) {
        echo '<script>' . $callback . '(' . json_encode($info) . ')</script>';
    } else {
        echo json_encode($info);
    }
});
