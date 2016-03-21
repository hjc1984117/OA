<?php

$GLOBALS['/common/log.php'] = 1;

function write_log($catagory, $text, $level = "info") {
    $folder_path = $_SERVER["DOCUMENT_ROOT"] . "/log/$catagory";
    if (!is_dir($folder_path)) mkdir($folder_path, 0777, true);
    $date = date("Y-m-d");
    $file_path = "$folder_path/$date.log";
    $log_text = sprintf("[%s.%s][$level]%s\n", date("Y-m-d H:i:s"), milliseconds(), $text);
    return file_put_contents($file_path, $log_text, FILE_APPEND);
}

function exception_log($catagory, $title, \Exception $ex) {
    $folder_path = $_SERVER["DOCUMENT_ROOT"] . "/exception-log/$catagory";
    if (!is_dir($folder_path)) mkdir($folder_path, 0777, true);
    $date = date("Y-m-d");
    $file_path = "$folder_path/$date.log";
    $messages = array($ex->getMessage());
    while (null !== ($inner_exception = $ex->getPrevious())) {
        $messages[] = $inner_exception->getMessage();
    }
    $log_text = implode('\n', $messages);
    $log_text = sprintf("[%s.%s]$title\n%s", date("Y-m-d H:i:s"), milliseconds(), $log_text);
    return file_put_contents($file_path, $log_text, FILE_APPEND);
}
