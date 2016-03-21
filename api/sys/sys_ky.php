<?php

/**
 * 系统设置
 *
 * @author YanXiong
 * @copyright 2015 星密码
 * @version 2015/5/21
 */
require '../../application.php';
require '../../loader-api.php';
require '../../common/SysKV.php';

check_token(null);

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    
});
execute_request(HttpRequestMethod::Post, function() use($action) {
    if ($action == 3) {
        $value = request_string("value");
        $key = request_string("key");
        echo_result(SysKV::updateValueByKey($key, $value));
    }
});
