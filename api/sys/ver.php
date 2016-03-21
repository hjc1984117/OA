<?php

/**
 * OA客户端版本检查
 *
 * @author ChenHao
 * @copyright 2015 星密码集团
 * @version 2015/09/08
 */
require '../../application.php';
require '../../loader-api.php';

list($action, $time, $version, $sign) = filter_request(array(
    request_action(),
    request_datetime('time'),
    request_string('version'),
    request_md5_32('sign')));
$sign_str = $action . $time . $version . CLIENT_AES_KEY;
if (!str_equals($sign, md5($sign_str))) die_error(USER_ERROR, '在检查更新时发生错误，请重启软件~');
if ($action == 1) {
    //echo_code(str_equals($version, CLIENT_VERSION) ? 0 : 1);
    echo str_equals($version, CLIENT_VERSION) ? 0 : 1;
}
if ($action == 2) {
//    $update_filename_array = array(
//        'YCOAResource.dll',
//        'YCLib.dll',
//        'YCOAClient.exe',
//        'DSkin.dll');
    //echo_result(array('filenames' => $update_filename_array));
    //echo CLIENT_DOWNLOAD_URL . '$' . implode('|', $update_filename_array);
    echo CLIENT_DOWNLOAD_URL . 'update_' . date('Ymd') . '.zip';
}
