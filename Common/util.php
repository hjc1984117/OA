<?php

/**
 * 实用工具函数库
 */
$GLOBALS['/common/util.php'] = 1;

// 生成GUID
function randomGUID() {
    $workid = strtoupper(md5(uniqid(mt_rand(), true)));

    $byte = hexdec(substr($workid, 12, 2));
    $byte = $byte & hexdec('0f');
    $byte = $byte | hexdec('40');
    $workid = substr_replace($workid, strtoupper(dechex($byte)), 12, 2);

    $byte = hexdec(substr($workid, 16, 2));
    $byte = $byte & hexdec('3f');
    $byte = $byte | hexdec('80');
    $workid = substr_replace($workid, strtoupper(dechex($byte)), 16, 2);

    $wid = substr($workid, 0, 8) . '-'
            . substr($workid, 8, 4) . '-'
            . substr($workid, 12, 4) . '-'
            . substr($workid, 16, 4) . '-'
            . substr($workid, 20, 12);

    return $wid;
}

// 不区分大小写的in_array实现
function in_array_case($value, $array) {
    return in_array(strtolower($value), array_map('strtolower', $array));
}

/**
 * 拼接路径
 * @param string $first 第一路径(根路径)
 * @param string $second 第二路径
 * @param string $separate 分隔符
 * @return string
 */
function path_combine($first, $second, $separate = PATH_SEPARATOR) {
    $first = realpath($first);
    $first = rtrim($first, $separate);
    $second = ltrim($second, $separate);
    return $first . $separate . $second;
}

/**
 * base64编码
 * @param string $str 源字符串
 * @return string
 */
function urlsafe_base64_encode($str) {
    $find = array("+", "/");
    $replace = array("-", "_");
    return str_replace($find, $replace, base64_encode($str));
}

function htmlencode($str) {
    return htmlentities(urldecode($str), ENT_COMPAT, 'UTF-8');
}

function strmask($str, $start, $end) {
    if (!isset($str)) return $str;
    if (strlen($str) === 0) return $str;
    $length = strlen($str);
    $mask = str_repeat('*', $length);
    if ($start > -1) {
        for ($i = 0; $i < $start; $i++) {
            $mask{$i} = $str{$i};
        }
    }
    if ($end > -1) {
        for ($i = $length - 1; $i >= $length - $end; $i--) {
            $mask{$i} = $str{$i};
        }
    }

    return $mask;
}

function md5_16($str) {
    return substr(md5($str), 8, 16);
}

function str_length($str) {
    return mb_strlen($str, 'utf8');
}

//字符串指定长度校验
function str_length_range($str, $min_length = null, $max_length = null, $equals = true) {
    $str_len = str_length($str);
    if (isset($min_length) && isset($max_length)) $res = $str_len > $min_length && $str_len < $max_length;
    elseif (isset($min_length) && !isset($max_length)) $res = $str_len > $min_length;
    elseif (!isset($min_length) && isset($max_length)) $res = $str_len < $max_length;
    else return false;
    return $equals ? ($res || $str_len == $min_length || $str_len == $max_length) : $res;
}

//数字指定大小校验
function numeric_range($num, $min = null, $max = null, $equals = true) {
    if (isset($min) && isset($max)) $res = $num > $min && $num < $max;
    elseif (isset($min) && !isset($max)) $res = $num > $min;
    elseif (!isset($min) && isset($max)) $res = $num < $max;
    else return false;
    return $equals ? ($res || $num == $min || $num == $max) : $res;
}

function gbk_2_utf8($str) {
    if (mb_detect_encoding($str, "ASCII,GB2312,GBK,UTF-8") == "EUC-CN") return iconv("GB2312", "UTF-8//IGNORE", $str);
    return $str;
}

function get_bool_string($bool) {
    return $bool ? "true" : "false";
}

//验证时间
function is_datetime($value) {
    return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[0-9]{4}(\-|\/)[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}(|:[0-9]{1,2})))$/')));
}

//验证邮箱
function is_email($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL);
}

//验证URL
function is_url($value) {
    return filter_var($value, FILTER_VALIDATE_URL);
}

//验证IP
function is_ip($value) {
    return filter_var($value, FILTER_VALIDATE_IP);
}

//验证32位MD5
function is_md5_32($value) {
    return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[0-9a-fA-F]{32,32}$/')));
}

//验证16位MD5
function is_md5_16($value) {
    return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[0-9a-fA-F]{16,16}$/')));
}

//验证手机号
function is_mobilephone($value) {
    return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^1[3458][0-9]{9,9}$/')));
}

//验证座机
function is_telephone($value) {
    return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^\d{3,4}-\d{7,8}$/')));
}

//验证邮编
function is_zip($value) {
    return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => '/^[1-9][0-9]{5}$/')));
}

function filter_regexp($value, $regexp) {
    return filter_var($value, FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $regexp)));
}

//数字校验，可返回指定默认值
function filter_numeric(&$value, $default_value = 0) {
    if (!isset($value) || !is_numeric($value) || $value < 0) $value = $default_value;
}

//取毫秒数
function milliseconds() {
    list($usec, $sec) = explode(" ", microtime());
    $msec = round($usec * 1000);
    return $msec;
}

/**
 * 字符串比较
 * @param string $str1 字符串1
 * @param string $str2 字符串2
 * @param string $case 是否忽略大小写
 * @return bool 是否相等（true or false）
 */
function str_equals($str1, $str2, $case = false) {
    if ($case) return strcasecmp($str1, $str2) === 0;
    else return strcmp($str1, $str2) === 0;
}

function parse_date($time) {
    $date = date_create($time);
    if ($date) return array(true, 'date' => $date);
    $e = date_get_last_errors();
    $msgs = array();
    foreach ($e['warnings'] as $key => $date) {
        $msgs[] = "[WARN][$key]=>[$date]";
    }
    foreach ($e['errors'] as $key => $date) {
        $msgs[] = "[ERROR][$key]=>[$date]";
    }
    return array(false, 'msg' => implode(', ', $msgs));
}

/**
 * XML编码
 * @param mixed $data 数据
 * @param string $root 根节点名
 * @param string $item 数字索引的子节点名
 * @param string $attr 根节点属性
 * @param string $id   数字索引子节点key转换的属性名
 * @param string $encoding 数据编码
 * @return string
 */
function xml_encode($data, $root = 'response', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8') {
    if (is_array($attr)) {
        $_attr = array();
        foreach ($attr as $key => $value) {
            $_attr[] = "{$key}=\"{$value}\"";
        }
        $attr = implode(' ', $_attr);
    }
    $attr = trim($attr);
    $attr = empty($attr) ? '' : " {$attr}";
    $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
    $xml .= "<{$root}{$attr}>";
    $xml .= data_to_xml($data, $item, $id);
    $xml .= "</{$root}>";
    return $xml;
}

/**
 * 数据XML编码
 * @param mixed  $data 数据
 * @param string $item 数字索引时的节点名称
 * @param string $id   数字索引key转换为的属性名
 * @return string
 */
function data_to_xml($data, $item = 'item', $id = 'id') {
    $xml = $attr = '';
    foreach ($data as $key => $val) {
        if (is_numeric($key)) {
            $id && $attr = " {$id}=\"{$key}\"";
            $key = $item;
        }
        $xml .= "<{$key}{$attr}>";
        $xml .= (is_array($val) || is_object($val)) ? data_to_xml($val, $item, $id) : $val;
        $xml .= "</{$key}>";
    }
    return $xml;
}

/**
 * 加载配置文件 支持格式转换 仅支持一级配置
 * @param string $file 配置文件名
 * @param string $parse 配置解析方法 有些格式需要用户自己解析
 * @return void
 */
function load_config($file, callable $parse) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);
    switch ($ext) {
        case 'php':
            return include $file;
        case 'ini':
            return parse_ini_file($file);
        case 'xml':
            return (array) simplexml_load_file($file);
        case 'json':
            return json_decode(file_get_contents($file), true);
        default:
            return $parse($file);
    }
}

/**
 * 字符串截取函数
 * @param string $str 原始字符串
 * @param string $start_str 起始字符串
 * @param string $end_str 结束字符串
 * @return 截取后的字符串
 */
function cut_str($str, $start_str = '', $end_str = '') {
    $start_str_index = strpos($str, $start_str, 0);
    $end_str_index = strpos($str, $end_str, $start_str_index);
    return substr($str, $start_str_index + strlen($start_str), $end_str_index - ($start_str_index + strlen($start_str)));
}

/**
 * Discuz 中经典的加密解密函数
 * @param string $string 原文或者密文
 * @param string $operation 操作(ENCODE | DECODE), 默认为 DECODE
 * @param string $key 密钥
 * @param int $expiry 密文有效期, 加密时候有效， 单位 秒，0 为永久有效
 * @return string 处理后的 原文或者 经过 base64_encode 处理后的密文
 *
 * @example
 *
 * $a = authcode('abc', 'ENCODE', 'key');
 * $b = authcode($a, 'DECODE', 'key');  // $b(abc)
 *
 * $a = authcode('abc', 'ENCODE', 'key', 3600);
 * $b = authcode('abc', 'DECODE', 'key'); // 在一个小时内，$b(abc)，否则 $b 为空
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 3600) {

    $ckey_length = 4;
    // 随机密钥长度 取值 0-32;
    // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
    // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
    // 当此值为 0 时，则不产生随机密钥

    $key = md5($key ? $key : 'key' ); //这里可以填写默认key值
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), - $ckey_length)) : '';

    $cryptkey = $keya . md5($keya . $keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0 ) . substr(md5($string . $keyb), 0, 16) . $string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for ($i = 0; $i <= 255; $i ++) {
        $rndkey [$i] = ord($cryptkey [$i % $key_length]);
    }

    for ($j = $i = 0; $i < 256; $i ++) {
        $j = ($j + $box [$i] + $rndkey [$i]) % 256;
        $tmp = $box [$i];
        $box [$i] = $box [$j];
        $box [$j] = $tmp;
    }

    for ($a = $j = $i = 0; $i < $string_length; $i ++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box [$a]) % 256;
        $tmp = $box [$a];
        $box [$a] = $box [$j];
        $box [$j] = $tmp;
        $result .= chr(ord($string [$i]) ^ ($box [($box [$a] + $box [$j]) % 256]));
    }

    if ($operation == 'DECODE') {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        return $keyc . str_replace('=', '', base64_encode($result));
    }
}

/**
 * 获取中文字符拼音首字母
 * @param string $str 原始字符串
 * @return 首字母
 */
function get_first_letter($str) {
    if (!isset($str)) return null;
    $fchar = ord($str{0});
    if ($fchar >= ord('A') && $fchar <= ord('z')) return strtoupper($str{0});
    $s1 = iconv('UTF-8', 'gb2312', $str);
    $s2 = iconv('gb2312', 'UTF-8', $s1);
    $s = $s2 == $str ? $s1 : $str;
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if ($asc == -7226) return 'Y';
    if ($asc >= -20319 && $asc <= -20284) return 'A';
    if ($asc >= -20283 && $asc <= -19776) return 'B';
    if ($asc >= -19775 && $asc <= -19219) return 'C';
    if ($asc >= -19218 && $asc <= -18711) return 'D';
    if ($asc >= -18710 && $asc <= -18527) return 'E';
    if ($asc >= -18526 && $asc <= -18240) return 'F';
    if ($asc >= -18239 && $asc <= -17923) return 'G';
    if ($asc >= -17922 && $asc <= -17418) return 'H';
    if ($asc >= -17417 && $asc <= -16475) return 'J';
    if ($asc >= -16474 && $asc <= -16213) return 'K';
    if ($asc >= -16212 && $asc <= -15641) return 'L';
    if ($asc >= -15640 && $asc <= -15166) return 'M';
    if ($asc >= -15165 && $asc <= -14923) return 'N';
    if ($asc >= -14922 && $asc <= -14915) return 'O';
    if ($asc >= -14914 && $asc <= -14631) return 'P';
    if ($asc >= -14630 && $asc <= -14150) return 'Q';
    if ($asc >= -14149 && $asc <= -14091) return 'R';
    if ($asc >= -14090 && $asc <= -13319) return 'S';
    if ($asc >= -13318 && $asc <= -12839) return 'T';
    if ($asc >= -12838 && $asc <= -12557) return 'W';
    if ($asc >= -12556 && $asc <= -11848) return 'X';
    if ($asc >= -11847 && $asc <= -11056) return 'Y';
    if ($asc >= -11055 && $asc <= -10247) return 'Z';
    return null;
}

/**
 * 获取中文字符拼音首字母（按照A-E、F-J、K-O、P-T、U-Z分组）
 * @param string $str 原始字符串
 * @return 首字母
 */
function get_first_letter_group($str) {
    if (!isset($str)) return null;
    $letter_group_map = array(
        'A-E' => array('A', 'B', 'C', 'D', 'E'),
        'F-J' => array('F', 'G', 'H', 'I', 'J'),
        'K-O' => array('K', 'L', 'M', 'N', 'O'),
        'P-T' => array('P', 'Q', 'R', 'S', 'T'),
        'U-Z' => array('U', 'V', 'W', 'X', 'Y', 'Z')
    );
    $fchar = ord($str{0});
    if ($fchar >= ord('A') && $fchar <= ord('z')) {
        foreach ($letter_group_map as $key => $value) {
            if (in_array(strtoupper($str{0}), $value)) {
                return $key;
                break;
            }
        }
        //return strtoupper($str{0});
    }
    $s1 = iconv('UTF-8', 'gb2312', $str);
    $s2 = iconv('gb2312', 'UTF-8', $s1);
    $s = $s2 == $str ? $s1 : $str;
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    //die('asc : ' . $asc);
    if ($asc == -7226) return 'U-Z';
    if ($asc >= -20319 && $asc <= -18527) return 'A-E';
    if ($asc >= -18526 && $asc <= -16475) return 'F-J';
    if ($asc >= -16474 && $asc <= -14915) return 'K-O';
    if ($asc >= -14914 && $asc <= -12839) return 'P-T';
    if ($asc >= -12838 && $asc <= -10247) return 'U-Z';
    return null;
}

function array_sort_by_field(&$array, $field, $array_sort_order = SORT_ASC) {
    $arr_sort = array();
    foreach ($array as $key => $value) {
        $arr_sort[] = $value[$field];
    }
    array_multisort($arr_sort, $array_sort_order, $array);
}

function array_sort_by_ascii(&$array, $field, $array_sort_order = SORT_ASC) {
    $arr_sort = array();
    foreach ($array as $key => $value) {
        $s1 = iconv('UTF-8', 'gb2312', $value[$field]);
        $s2 = iconv('gb2312', 'UTF-8', $s1);
        $s = $s2 == $value[$field] ? $s1 : $value[$field];
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        $arr_sort[] = $asc;
    }
    array_multisort($arr_sort, $array_sort_order, $array);
}

/**
 * 计算两个时间差并返回差多少天、时、分、秒
 * @param $begin_time 起始时间戳
 * @param $end_time 结束时间戳
 * @return array 天、时、分、秒
 */
function time_diff($begin_time, $end_time) {
    time_2_timestamp($begin_time);
    time_2_timestamp($end_time);
    $timediff = $end_time - $begin_time;
    $days = intval($timediff / 86400);
    $remain = $timediff % 86400;
    $hours = intval($remain / 3600);
    $remain = $remain % 3600;
    $mins = intval($remain / 60);
    $secs = $remain % 60;
    $res = array("day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs);
    return $res;
}

/**
 * 计算两个时间差并返回差多少天、时、分
 * @param $begin_time 起始时间戳
 * @param $end_time 结束时间戳
 * @return array 天、时、分
 */
function time_diff_ceil($begin_time, $end_time) {
    time_2_timestamp($begin_time);
    time_2_timestamp($end_time);
    $timediff = $end_time - $begin_time;
    $days = ceil($timediff / 3600 / 24);
    $hours = ceil($timediff % (3600 / 24) / 3600);
    $mins = ceil($timediff % (3600 / 24) / 60);
    $res = array("day" => $days, "hour" => $hours, "min" => $mins);
    return $res;
}

/**
 *
 * 计算两个时间差并返回差多少天、时、分
 * @param $begin_time 起始时间戳
 * @param $end_time 结束时间戳
 * @param string $unit 单位（d、h、m）
 * return int
 */
function time_diff_unit($begin_time, $end_time, $unit = 'h') {
    time_2_timestamp($begin_time);
    time_2_timestamp($end_time);
    $timediff = $end_time - $begin_time;
    if (str_equals($unit, 'm')) $diff = ceil($timediff / 60); // 这里默认用ceil进1运算，如需要四舍五入，请用round
    elseif (str_equals($unit, 'h')) $diff = ceil($timediff / 3600);
    elseif (str_equals($unit, 'd')) $diff = ceil($timediff / 3600 / 24);
    return $diff;
}

/**
 *
 * 计算两个时间差并返回差多少月(m)、天(d)、时(h)、分(i)
 * @param $begin_time 起始时间戳
 * @param $end_time 结束时间戳
 * @param string $unit 单位（m、d、h、i）
 * return int
 */
function time_diff_unit_floor($begin_time, $end_time, $unit = 'm') {
    time_2_timestamp($begin_time);
    time_2_timestamp($end_time);
    $timediff = $end_time - $begin_time;
    if (str_equals($unit, 'i')) $diff = floor($timediff / 60);
    elseif (str_equals($unit, 'h')) $diff = floor($timediff / 3600);
    elseif (str_equals($unit, 'd')) $diff = floor($timediff / 3600 / 24);
    elseif (str_equals($unit, 'm')) $diff = floor($timediff / 3600 / 24 / 30);
    return $diff;
}

function time_2_timestamp(&$time) {
    //if (is_datetime($time)) $time = strtotime($time);
    if ($time instanceof \DateTime) $time = $time->getTimestamp();
    elseif (is_numeric($time)) {
        $time = $time;
    } else $time = strtotime($time);
}

/**
 * 可以统计中文字符串长度的函数
 * @param string $str 要计算长度的字符串
 * @return int 长度
 */
function abslength($str) {
    if (empty($str)) {
        return 0;
    }
    if (function_exists('mb_strlen')) {
        return mb_strlen($str, 'utf-8');
    } else {
        preg_match_all("/./u", $str, $ar);
        return count($ar[0]);
    }
}

/**
 * utf-8编码下截取中文字符串,参数可以参照substr函数
 * @param string $str 要进行截取的字符串
 * @param int $start 要进行截取的开始位置，负数为反向截取
 * @return string 截取之后的字符串
 */
function utf8_substr($str, $start = 0) {
    if (empty($str)) {
        return false;
    }
    if (function_exists('mb_substr')) {
        if (func_num_args() >= 3) {
            $end = func_get_arg(2);
            return mb_substr($str, $start, $end, 'utf-8');
        } else {
            mb_internal_encoding("UTF-8");
            return mb_substr($str, $start);
        }
    } else {
        $null = "";
        preg_match_all("/./u", $str, $ar);
        if (func_num_args() >= 3) {
            $end = func_get_arg(2);
            return join($null, array_slice($ar[0], $start, $end));
        } else {
            return join($null, array_slice($ar[0], $start));
        }
    }
}

/**
 * 字符串拆解成字符数组
 * @param string $str 要拆解的字符串
 * @return array 拆解之后的数组
 */
function str_to_arr($str = '') {
    $arr = array();
    $str_length = abslength($str);
    for ($i = 0; $i < $str_length; $i++) {
        $arr[] = utf8_substr($str, $i, 1);
    }
    return $arr;
}

/**
 * 根据阀值调整时间，例如：阀值设置为"03:00:00"，那么0:00:00到02:59:59之间的时间算昨天的
 * @param string $format 时间格式，默认值"Y-m-d"
 * @param string $threshold 时间阀值，默认值"03:00:00"
 * @return string 日期字符串
 */
function adjust_time($format = 'Y-m-d', $threshold = '03:00:00') {
    if (time() < strtotime($threshold)) {
        return date($format, strtotime('-1 day'));
    } else {
        return date($format);
    }
}

/**
 * 获取给定日期的前一天
 * @param string $date
 * @return string $yesterday
 */
function get_yesterday($date) {
    if (empty($date)) {
        $yesterday = date('Y-m-d', strtotime('-1 day'));
    } else {
        $arr = explode('-', $date);
        $year = $arr[0];
        $month = $arr[1];
        $day = $arr[2];
        $unixtime = mktime(0, 0, 0, $month, $day, $year) - 86400;
        $yesterday = date('Y-m-d', $unixtime);
    }
    return $yesterday;
}

/**
 * 获取给定日期的后一天
 * @param string $date
 * @return string $yesterday
 */
function get_tomorrow($date) {
    if (empty($date)) {
        $yesterday = date('Y-m-d', strtotime('+1 day'));
    } else {
        $arr = explode('-', $date);
        $year = $arr[0];
        $month = $arr[1];
        $day = $arr[2];
        $unixtime = mktime(0, 0, 0, $month, $day, $year) + 86400;
        $yesterday = date('Y-m-d', $unixtime);
    }
    return $yesterday;
}

/**
 * 替换指定数组的所有字符
 * @param array $search_str_array 需要替换的字符串数组
 * @param string $replace 替换后的字符
 * @param string $subject 要替换的原始字符串
 * @return string 替换后的字符串
 */
function strs_replace(array $search_str_array, $replace, $subject) {
    foreach ($search_str_array as $search) {
        $subject = str_replace($search, $replace, $subject);
    }
    return $subject;
}
