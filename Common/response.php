<?php

/**
 * 响应通用函数库
 *
 * @author ChenHao
 * @version 2014/12/15
 */
$GLOBALS['/common/response.php'] = 1;

function js_alert($msg) {
    header('Content-Type: text/html; charset=utf-8');
    die("<script>alert('$msg');window.close();</script>");
}

function redirect_302($url) {
    header("Location:$url");
    $error = error_get_last();
    if ($error['type'] > 0) {
        echo '<script>window.location="' . $url . '";</script>';
    }
    exit(0);
}

function die_http_client_error($error_code, $msg, $http_result = null, $response_code = null) {
    $result = array('code' => $error_code, 'msg' => $msg);
    if (isset($http_result)) $result = array_merge($result, array('sub_code' => $http_result['status'], 'sub_msg' => $http_result['error']));
    die(get_response($result, $response_code));
}

function die_pdo_error($error_code, $msg, $pdo_result = null, $response_code = null) {
    $result = array('code' => $error_code, 'msg' => $msg);
    if (isset($pdo_result)) $result = array_merge($result, array('sub_code' => $pdo_result['status'], 'sub_msg' => $pdo_result['info'], 'detail' => $pdo_result['detail']));
    die(get_response($result, $response_code));
}

function die_error($error_code, $msg, $response_code = null) {
    $result = array('code' => $error_code, 'msg' => $msg);
    die(get_response($result, $response_code));
}

function echo_result($result) {
    exit(get_response($result));
}

function echo_list_result($result, $models, $data = array(), $list_root = 'list') {
    $page_no = request_pageno();
    $total = $result['total_count'];
    $max_page_no = ceil($total / request_pagesize());
    $result = array('total_count' => $total, "$list_root" => $models, 'page_no' => $page_no, 'max_page_no' => $max_page_no, 'code' => 0);
    $result = array_merge($result, $data);
    exit(get_response($result));
}

function echo_jsonp_result($result, $jsonp) {
    exit($jsonp . '(' . json_encode($result) . ')');
}

function echo_code($code) {
    exit(get_response(array('code' => $code)));
}

function echo_msg($msg) {
    exit(get_response(array('code' => 0, 'msg' => $msg)));
}

function get_response($data, $response_code = null) {
    if (ONLY_ALLOW_DEFAULT_CONTENT_TYPE) {
        $type = DEFAULT_CONTENT_TYPE;
    } else {
        $type = get_accept_type();
        if (strlen($_GET['format']) > 0 && in_array(strtolower($_GET['format']), array('json', 'xml'))) {
            $type = $_GET['format'];
        }
    }
    $charset = DEFAULT_CHARSET;
    if (isset($response_code)) custom_http_response_code($response_code);
    set_content_type($type, $charset);
    if (DEBUG_MODE && strcasecmp($_REQUEST['xdebug'], 'true') === 0) {
        var_dump($data);
    }
    //if (empty($data)) return '';
    if (strcasecmp('json', $type) === 0) {
        $data = json_encode($data);
    } elseif (strcasecmp('xml', $type) === 0) {
        $data = xml_encode($data);
    } elseif (strcasecmp('html', $type) === 0) {
        if (is_array($data)) {
            $data = serialize($data);
        }
    }
    return $data;
}

function set_content_type($type = DEFAULT_CONTENT_TYPE, $charset = DEFAULT_CHARSET) {
    if (headers_sent()) return;
    $content_types = array(
        'xml' => 'application/xml',
        'json' => 'application/json',
        'html' => 'text/html',
    );
    header('Content-Type: ' . $content_types[strtolower($type)] . '; charset=' . $charset);
}

function get_accept_type() {
    $acc = $_SERVER['HTTP_ACCEPT'];
    $types = array(
        'json' => 'application/json,text/x-json,application/jsonrequest,text/json',
        'xml' => 'application/xml,text/xml,application/x-xml',
        'text' => 'text/plain',
        'image' => 'image/webp'
//        'js' => 'text/javascript,application/javascript,application/x-javascript',
//        'css' => 'text/css',
//        'rss' => 'application/rss+xml',
//        'yaml' => 'application/x-yaml,text/yaml',
//        'csv' => 'text/csv'
    );
    foreach ($types as $key => $val) {
        $array = explode(',', $val);
        foreach ($array as $v) {
            if (strpos($_SERVER['HTTP_ACCEPT'], $v) != false) {
                return $key;
            }
        }
    }
    return DEFAULT_CONTENT_TYPE;
}

// 发送Http状态信息（此处借用TP源码）
function custom_http_response_code($response_code = null) {
    static $_status = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily ', // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',
        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded',
        999 => 'unknow'
    );
    if (isset($response_code) && isset($_status[$response_code])) {
        header('HTTP/1.1 ' . $response_code . ' ' . $_status[$response_code]);
        // 确保FastCGI模式下正常
        header('Status:' . $response_code . ' ' . $_status[$response_code]);
    }
}
