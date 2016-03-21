<?php

/**
 * 请求通用函数库
 *
 * @author ChenHao
 * @version 2014/12/15
 */
$GLOBALS['/common/request.php'] = 1;

global $_PUT, $_DELETE, $_REQUEST_OBJECT, $_REQUEST_ARRAY, $_LOGIN_USER;

if (isset($_COOKIE['YCOA_SESSION_KEY'])) {
    $session_key = authcode($_COOKIE['YCOA_SESSION_KEY'], 'DECODE', PRIVATE_KEY, 3600 * SESSION_EXPIRE_HOUR);
    if (isset($session_key)) $_LOGIN_USER = json_decode($session_key, true);
}

switch (strtoupper($_SERVER['REQUEST_METHOD'])) {
    case 'GET':
        break;
    case 'POST':
        //parse_str(file_get_contents('php://input'), $_REQUEST_ARRAY);
        $php_input = file_get_contents('php://input');
        $_REQUEST_OBJECT = json_decode($php_input);
        $_REQUEST_ARRAY = json_decode($php_input, true);
        if (!empty($_REQUEST_ARRAY)) $_REQUEST = array_merge($_REQUEST, $_REQUEST_ARRAY);
        break;
//    case 'PUT':
//        $_PUT = $_REQUEST_ARRAY;
//        break;
//    case 'DELETE':
//        $_DELETE = $_REQUEST_ARRAY;
//        break;
    default:
        die_error(UNSUPPORTED_REQUEST_METHOD_ERROR, 'Unsupported request method');
}

if (!empty($_REQUEST)) inject_check($_REQUEST);

//防SQL注入攻击
function inject_check($rearray) {
    foreach ($rearray as $key => $value) {
        if (is_array($value)) {
            inject_check($value);
        } else {
            //$check = preg_match('/select|insert|update|delete|truncate|drop|\'|\\*|\*|\.\.\/|\.\/|union|into|load_file|outfile|\$\{|\%24\{|\$\%7b|\%24\%7b/i', $value);
            $check = preg_match('/^select$|^insert$|^update$|^delete$|^truncate$|^drop$|^union$|^into$|^load_file$|^outfile$/i', $value);
            if ($check) {
                die_error(PARAM_ILLEGAL_ERROR_CODE, PARAM_ILLEGAL_ERROR_MSG);
            }
        }
    }
}

$is_user_login_api = (str_equals($_SERVER['PHP_SELF'], '/api/user/user.php') && request_action() == 1 && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['remember'])) || str_equals($_SERVER['PHP_SELF'], '/api/user/quicklogin.php');
$is_sys_api = strpos($_SERVER['PHP_SELF'], '/api/sys/') !== false;
$is_cron_api = strpos($_SERVER['PHP_SELF'], '/api/cron/') !== false;
$is_client_api = strpos($_SERVER['PHP_SELF'], '/api/client/') !== false;
$is_page_url = strpos($_SERVER['PHP_SELF'], '/page/') !== false && strpos($_SERVER['PHP_SELF'], '.php') !== false;
if ($is_page_url) check_token(null, false, '/page/login.html');
if (!$is_user_login_api && !$is_sys_api && !$is_cron_api && !$is_client_api) check_token(null);

function execute_request($http_request_method, callable $func) {
    if (strcasecmp($_SERVER['REQUEST_METHOD'], $http_request_method) === 0) {
        $func();
        exit(0);
    }
}

function execute_action($http_request_method, $action, callable $func) {
    if (strcasecmp($_SERVER['REQUEST_METHOD'], $http_request_method) === 0 && request_action() === $action) {
        $func();
        //exit(0);
    }
}

/**
 * 包含指定action时执行
 * @param array $action_ids 指定action数组
 * @param callable $func 执行函数
 */
function execute_include_action(array $action_ids, callable $func) {
    if (in_array(request_action(), $action_ids)) {
        $func();
    }
}

/**
 * 不包含指定action时执行
 * @param array $action_ids 指定action数组
 * @param callable $func 执行函数
 */
function execute_exclude_action(array $action_ids, callable $func) {
    if (!in_array(request_action(), $action_ids)) {
        $func();
    }
}

//输入参数验证
function filter_request(array $args, $response_code = null) {
    if (in_array(null, $args, true)) die_error(PARAM_MISSING_ERROR_CODE, PARAM_MISSING_ERROR_MSG, $response_code);
    if (in_array(false, $args, true)) die_error(PARAM_INVALID_ERROR_CODE, PARAM_INVALID_ERROR_MSG, $response_code);
    return $args;
}

function request_string($name, $decode = false, $sanitize_special_chars = false) {
    $value = $_REQUEST[$name];
    if (!isset($value)) return null;
    if ($decode) $value = urldecode($value);
    if ($sanitize_special_chars) $value = filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS);
    $value = trim($value);
    if (strlen($value) <= 0) return null;
    return $value;
}

function request_email($name) {
    $request_string = request_string($name, true);
    return !isset($request_string) ? null : filter_var($request_string, FILTER_VALIDATE_EMAIL);
}

function request_url($name) {
    $request_string = request_string($name);
    return !isset($request_string) ? null : filter_var($request_string, FILTER_VALIDATE_URL);
}

function request_ip($name) {
    $request_string = request_string($name);
    return !isset($request_string) ? null : filter_var($request_string, FILTER_VALIDATE_IP);
}

function request_datetime($name) {
    $request_string = request_string($name);
    return !isset($request_string) ? null : filter_var($request_string, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[0-9]{4}(\-|\/)[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/')));
}

function request_boolean($name) {
    $request_string = request_string($name);
    return !isset($request_string) ? null : filter_var($request_string, FILTER_VALIDATE_BOOLEAN);
}

function request_md5_32($name) {
    $request_string = request_string($name);
    return !isset($request_string) ? null : filter_var($request_string, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[0-9a-fA-F]{32,32}$/')));
}

function request_md5_16($name) {
    $request_string = request_string($name);
    return !isset($request_string) ? null : filter_var($request_string, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[0-9a-fA-F]{16,16}$/')));
}

function request_int($name, $min = null, $max = null) {
    $request_string = request_string($name);
    if (!isset($request_string)) return null;
    if (isset($min) || isset($max)) {
        $options = array();
        if (isset($min)) $options['min_range'] = $min;
        if (isset($max)) $options['max_range'] = $max;
        return filter_var($request_string, FILTER_VALIDATE_INT, array('options' => $options));
    }

    return filter_var($request_string, FILTER_VALIDATE_INT);
}

function request_float($name) {
    $request_string = request_string($name);
    return !isset($request_string) ? null : filter_var($request_string, FILTER_VALIDATE_FLOAT);
}

function request_money($name) {
    $request_string = request_string($name);
    return !isset($request_string) ? null : filter_var($request_string, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^(([1-9]{1}\d*)|([0]{1}))(\.(\d){1,2})?$/')));
}

function request_mobilephone($name) {
    $request_string = request_string($name);
    return !isset($request_string) ? null : filter_var($request_string, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^1[3458][0-9]{9,9}$/')));
}

function request_telephone($name) {
    $request_string = request_string($name);
    return !isset($request_string) ? null : filter_var($request_string, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\d{3,4}-\d{7,8}$/')));
}

function request_zip($name) {
    $request_string = request_string($name);
    return !isset($request_string) ? null : filter_var($request_string, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[1-9][0-9]{5}$/')));
}

function request_numeric($name, $min_length = 1, $max_length = null) {
    $request_string = request_string($name);
    if (!isset($request_string)) return null;
    return filter_var($request_string, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\d{' . $min_length . ',' . $max_length . '}$/')));
}

function request_regexp($name, $regexp) {
    $request_string = request_string($name);
    return !isset($request_string) ? null : filter_var($request_string, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $regexp)));
}

function default_value($name, $value) {
    $request_string = request_string($name);
    return $request_string ? $request_string : $value;
}

function request_user() {
    global $_LOGIN_USER;
    return $_LOGIN_USER;
}

function request_login_userid() {
    return request_user()['userid'];
}

function request_login_token() {
    return request_user()['token'];
}

function request_login_username() {
    return request_user()['username'];
}

function request_userid() {
    $userid = request_int('userid');
    if (!isset($userid)) $userid = request_user()['userid'];
    return $userid;
}

function request_token() {
    $token = request_md5_32('token');
    if (!isset($token)) $token = request_user()['token'];
    return $token;
}

function request_username() {
    $username = request_string('username');
    if (!isset($username)) $username = request_user()['username'];
    return $username;
}

function request_action() {
    return request_int('action');
}

function request_pageno() {
    $pageno = request_int('pageno');
    filter_numeric($pageno, 1);
    return $pageno;
}

function request_pagesize() {
    $pagesize = request_int('pagesize');
    filter_numeric($pagesize, DEFAULT_PAGESIZE);
    return $pagesize;
}

function get_request_ip() {
    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    if (empty($ip)) $ip = $_SERVER["REMOTE_ADDR"];
    return $ip;
}

function request_object() {
    global $_REQUEST_OBJECT;
    return $_REQUEST_OBJECT;
}

function request_array() {
    global $_REQUEST_ARRAY;
    return $_REQUEST_ARRAY;
}

function request_object_id() {
    $reg_match_result = preg_match('/(?<=.php\/)\d{1,}(?=\/)/', $_SERVER['REQUEST_URI'] . '/', $object_id_match);
    $object_id = $reg_match_result;
    if ($object_id) {
        if (isset($object_id_match[0])) {
            $object_id = $object_id_match[0];
            $object_id = filter_var($object_id, FILTER_VALIDATE_INT);
        }
    }
    if (!$object_id) die_error(USER_ERROR, '对象ID为空');
    return $object_id;
}

class HttpRequestMethod {

    const __default = self::Get;
    const Get = 'GET';
    const Post = 'POST';
    const Put = 'PUT';
    const Delete = 'DELETE';

}
