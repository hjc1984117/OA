<?php

require '../../application.php';
require '../../loader-api.php';
header("Content-type:text/html;charset=utf-8");

$file_name = $_REQUEST['filename'];
$title = $_REQUEST['title'];
if (isset($file_name)) $file_name = base64_decode($file_name);
$file_path = "";
$array = explode('.', $file_name);
$filename = $array[count($array) - 1];
if ($filename == 'pdf') {
    $file_path = DEFAULT_PDF_OUTPUT_DIR . $file_name;
} else {
    $file_path = DEFAULT_FILE_UPLOAD_DIR . $file_name;
}
$fp = fopen($file_path, "r");
$file_size = filesize($file_path);
//下载文件需要用到的头 
Header("Content-type: application/octet-stream");
Header("Accept-Ranges: bytes");
Header("Accept-Length:" . $file_size);
Header("Content-Disposition: attachment; filename=" . iconv("utf-8", "gb2312", $title) . '.' . $array[count($array) - 1]);
$buffer = 1024;
$file_count = 0;
//向浏览器返回数据 
while (!feof($fp) && $file_count < $file_size) {
    $file_con = fread($fp, $buffer);
    $file_count+=$buffer;
    echo $file_con;
}
fclose($fp);