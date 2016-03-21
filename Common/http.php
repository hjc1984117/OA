<?php

$GLOBALS['/common/http.php'] = 1;

function curl_httprequest($url, $method = 0, $postdata = "", $cookie = "", $allowredirect = 0, $referer = "", $useragent = "", $head = "", $timeout = 30) {
    if ($url == "") return false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, $method);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $allowredirect);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 7);
    if ($postdata != "") curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    if ($cookie != "") curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    if ($referer != "") curl_setopt($ch, CURLOPT_REFERER, $referer);
    if ($useragent != "") curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
    if ($head != "") curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    $ret = curl_exec($ch);
    $curl_info = curl_getinfo($ch);
    $info = $curl_info['url'] . '|' . $curl_info['http_code'] . '|' . $curl_info['total_time'];
    if ($curl_info['http_code'] == 0 || $curl_info['http_code'] >= 400) {
        $now = date('Y-m-d H:i:s');
        $uri = $_SERVER["REQUEST_URI"];
        $log_text = "[$now][uri:$uri][$info]";
        //wk_log($log_text, "function_curl");
    }
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($ret, 0, $headerSize);
    $body = substr($ret, $headerSize);
    if (preg_match("/msg> *(200|500) *</", $body) === 1) {
        //wk_log($_SERVER["REQUEST_URI"] . "|" . $url . "|" . $body, "200-500-log");
    }
    curl_close($ch);
    return array($body, $header, $info, "body" => $body, "header" => $header, "info" => $info);
}

function curl_http_request($url, $method = 0, $post_data = null, $cookie = null) {
    if (isset($post_data) && is_string($post_data) && strlen($post_data) > 0) $post_fields = $post_data;
    else if (isset($post_data) && is_array($post_data) && count($post_data) > 0) $post_fields = http_build_query($post_data, null, '&', PHP_QUERY_RFC3986);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, $method);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    if (isset($post_fields)) curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    if (isset($cookie)) curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    $result = curl_exec($ch);
    $curl_info = curl_getinfo($ch);
    $info = $curl_info['url'] . '|' . $curl_info['http_code'] . '|' . $curl_info['total_time'];
    $header_size = $curl_info['header_size'];
    $header = substr($result, 0, $header_size);
    $body = substr($result, $header_size);
    $success = true;
    $uri = $_SERVER["REQUEST_URI"];
    if ($curl_info['http_code'] == 0 || $curl_info['http_code'] >= 400) {
        $log_text = "[uri:$uri][$info][$header][$body]";
        //记录错误日志
        $success = false;
    }
    curl_close($ch);
    if ($success) {
        return $body;
    }
    return false;
}

function curl_http_post($url, $post_data = null) {
    $post_fields = "";
    if (isset($post_data) && is_string($post_data) && strlen($post_data) > 0) $post_fields = $post_data;
    else if (isset($post_data) && is_array($post_data) && count($post_data) > 0) $post_fields = http_build_query($post_data, null, '&', PHP_QUERY_RFC3986);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    $result = curl_exec($ch);
    $curl_info = curl_getinfo($ch);
    $info = $curl_info['url'] . '|' . $curl_info['http_code'] . '|' . $curl_info['total_time'];
    $header_size = $curl_info['header_size'];
    $header = substr($result, 0, $header_size);
    $body = substr($result, $header_size);
    $success = true;
    $uri = $_SERVER["REQUEST_URI"];
    if ($curl_info['http_code'] == 0 || $curl_info['http_code'] >= 400) {
        $log_text = "[uri:$uri][$info][$header][$body]";
        //write_log("curl-http-post", $log_text, "error");
        $success = false;
    }
    curl_close($ch);
    if ($success) {
        return $body;
    }
    return false;
}

function curl_upload_file_path($file_path) {
    $file_path = str_replace('\\', DIRECTORY_SEPARATOR, $file_path);
    $file_path = str_replace('/', DIRECTORY_SEPARATOR, $file_path);
    return "@$file_path";
}
