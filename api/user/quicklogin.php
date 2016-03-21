<?php

/**
 * 快捷登录
 *
 * @author ChenHao
 * @copyright 2015 星密码
 * @version 2015/5/04
 */
use Models\M_UserToken;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        list($time, $userid, $token, $sign) = filter_request(array(
            request_datetime('time'),
            request_userid(),
            request_token(),
            request_md5_32('sign')));
        $redirect_url = request_string('redirect_url');
        $menuid = request_string('menuid');
        $remember = request_boolean('remember');
        if (!isset($remember)) $remember = false;
        $time = urldecode($time);
        $sign_str = $time . $userid . $token . $action . PUBLIC_KEY;
        if (!str_equals($sign, md5($sign_str))) js_alert('签名错误');
        //if (abs(time() - strtotime($time)) > 300) js_alert('登录失败，您本地时间和服务器时间相差太大');
        if (!isset($redirect_url)) $redirect_url = '/page/index.html';
        $redirect_url = urldecode($redirect_url);
        if (strlen($menuid) > 0) $redirect_url.='?j=' . base64_encode($menuid);

        $userToken = new M_UserToken($userid);
        $userToken->set_query_fields(array(M_UserToken::$field_userid, M_UserToken::$field_employee_no, M_UserToken::$field_username, M_UserToken::$field_status, M_UserToken::$field_token));
        $userToken->set_where_and(M_UserToken::$field_token, SqlOperator::Equals, $token);
        $db = create_pdo();
        $result = $userToken->load($db, $userToken);
        if (!$result[0]) js_alert('登录失败，用户令牌错误');
        if ($userToken->get_status() == 3) js_alert('登录失败，当前状态禁止登录');

        //更新用户令牌
        set_cookie($db, $userToken, $remember);
        redirect_302($redirect_url);
    }
});
