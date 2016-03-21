<?php

u_require_once('/common/error-code.php');
u_require_once('/data/mapping-datas.php');
u_require_once('/data/generated-datas.php');
u_require_once('/data/workflow-datas.php');
u_require_once('/common/util.php');
u_require_once('/common/log.php');
u_require_once('/common/function.php');
u_require_once('/common/mysql.php');
u_require_once('/common/response.php');
u_require_once('/common/request.php');

function u_require_once($filename) {
    $filename = str_replace('\\', '/', $filename);
    isset($GLOBALS[$filename]) or require __DIR__ . $filename;
}
