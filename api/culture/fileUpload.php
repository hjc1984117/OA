<?php

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require '../../application.php';
require '../../loader-api.php';
require '../../Common/word2pdf.php';

$fileHZ = array(
    'application/msword',
    'application/vnd.ms-powerpoint',
    'application/vnd.ms-excel',
    'text/plain', 'text/html', 'image/jpeg', 'image/gif', 'application/pdf'
);
$targetDir = "";
$file = $_FILES["fileToUpload"];
if (!isset($file)) $file = $_FILES['editFileToUpload'];
if (!isset($file)) {
    echo json_decode(array('code' => -1, 'message' => '文件上传失败,请稍后重试~'));
}
$fileName = $file["name"];
$filesize = $file['size'];
$error_code = $file['error'];
if ($error_code == 0) {
    if ($filesize > (40 * 1024 * 1000)) { //限制上传大小 
        echo json_decode(array('code' => -1, 'message' => '文件大小不能超过40M~'));
    }
    if (!in_array($file['type'], $fileHZ)) {
        echo json_decode(array('code' => -1, 'message' => '文件格式不支持~'));
    }
    $array = explode('.', $fileName);
    $fileName = date("YmdHis") . rand(100, 999) . '.' . $array[count($array) - 1];
    if ($file['type'] == 'application/pdf') {
        $targetDir = DEFAULT_PDF_OUTPUT_DIR;
    } else {
        $targetDir = DEFAULT_FILE_UPLOAD_DIR;
    }
    if (!file_exists($targetDir)) {
        @mkdir($targetDir);
    }
    $cleanupTargetDir = true; // Remove old files
    $maxFileAge = 5 * 3600; // Temp file age in seconds
    $filePath = $targetDir . $fileName;
    $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
    $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
    if ($cleanupTargetDir) {
        if (!is_dir($targetDir) || !$dir = opendir($targetDir)) {
            echo json_decode(array('code' => -1, 'message' => '打开文件缓存目录失败~'));
        }
        while (($files = readdir($dir)) !== false) {
            $tmpfilePath = $targetDir . $files;
            // If temp file is current file proceed to the next
            if ($tmpfilePath == "{$filePath}.part") {
                continue;
            }
            // Remove temp file if it is older than the max age and is not the current file
            if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge)) {
                @unlink($tmpfilePath);
            }
        }
        closedir($dir);
    }
    // Open temp file
    if (!$out = @fopen("{$filePath}.part", $chunks ? "ab" : "wb")) {
        echo json_decode(array('code' => -1, 'message' => '读取文件流数据失败~'));
    }
    if (!empty($_FILES)) {
        if ($file["error"] || !is_uploaded_file($file["tmp_name"])) {
            echo json_decode(array('code' => -1, 'message' => '移动文件到上传目录失败~'));
        }
        // Read binary input stream and append it to temp file
        if (!$in = @fopen($file["tmp_name"], "rb")) {
            echo json_decode(array('code' => -1, 'message' => '打开文件流失败~'));
        }
    } else {
        if (!$in = @fopen("php://input", "rb")) {
            echo json_decode(array('code' => -1, 'message' => '打开文件流失败~'));
        }
    }
    while ($buff = fread($in, 4096)) {
        fwrite($out, $buff);
    }
    @fclose($out);
    @fclose($in);
    if (!$chunks || $chunk == $chunks - 1) {
        // Strip the temp .part suffix off 
        rename("{$filePath}.part", $filePath);
    }
    $size = round($filesize / 1024, 2); //转换成kb
    if ($file['type'] == 'application/pdf') {
        $arr = array(
            'code' => 0,
            'file_path' => trim($fileName),
            'pdf_file_name' => trim($fileName),
            'size' => $size
        );
    } else {
        $arr = array(
            'code' => 0,
            'file_path' => trim($fileName),
            'pdf_file_name' => trim($fileName . ".pdf"),
            'size' => $size
        );
        $word2pdf = new Word2Pdf();
        $word2pdf->getInitials($fileName);
    }
    echo json_encode($arr); //输出json数据 
} else {
    echo json_decode(array('code' => -1, 'message' => '文件上传失败~'));
}