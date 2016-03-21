<?php

/**
 * 用户相关操作
 *
 * @author ChenHao
 * @copyright 2015 星密码
 * @version 2015/2/11
 */
use Models\Base\Model;
use Models\M_User;
use Models\M_UserExt;
use Models\M_UserToken;
use Models\s_loginlog;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 0) {
        $reArray = array();
        $par = request_string("term");
        $par = strtoupper($par);
        $users = get_employees();
        foreach ($users as $user) {
            $py = strpos($user["py"], $par) !== false ? true : false;
            $name = strpos($user["username"], $par) !== false ? true : false;
            if ($py || $name) {
                array_push($reArray, $user);
            }
        }
        echo_result($reArray);
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    //$userData = request_object();
    //登录
    if ($action == 1) {
        list($username, $password) = filter_request(array(
            request_string('username'),
            request_md5_16('password')));
        $remember = request_boolean('remember');
        $login_type = request_int('type');
        filter_numeric($login_type, 0);
        if ($login_type == 1) {
            list($machineCode, $version) = filter_request(array(
                request_md5_32('machinecode'),
                request_string('version')));
            if (!str_equals(CLIENT_VERSION, $version)) {
                $result = array('Code' => 'VER_UPDATE_CODE', 'Msg' => '请更新至最新版本再登录');
                die(get_response($result));
            }
        }
        if (!filter_regexp($username, '/^(YC|ZH)\d{5}$/')) die_error(USER_ERROR, '登录失败，用户名或密码错误'); //die_error(USER_ERROR, '登录失败，请输入正确的员工编号');

        $userToken = new M_UserToken();
        $userToken->set_query_fields(array(M_UserToken::$field_userid, M_UserToken::$field_employee_no, M_UserToken::$field_username, M_UserToken::$field_status, M_UserToken::$field_token, M_UserToken::$field_avatar));

//        if (filter_regexp($username, '/^YC\d{5}$/i')) {
//            $userToken->set_where_and(M_UserToken::$field_employee_no, SqlOperator::Equals, $username);
//        } else {
//            $userToken->set_where_and(M_UserToken::$field_username, SqlOperator::Equals, $username);
//        }

        $userToken->set_where_and(M_UserToken::$field_employee_no, SqlOperator::Equals, $username);
        $userToken->set_where_and(M_UserToken::$field_password, SqlOperator::Equals, $password);

        $db = create_pdo();
        $result = $userToken->load($db, $userToken);
        if (!$result[0]) die_error(USER_ERROR, '登录失败，用户名或密码错误');
        if ($userToken->get_status() == 3) die_error(USER_ERROR, '登录失败，当前状态禁止登录');

        //更新用户令牌
        if ($login_type != 1) set_cookie($db, $userToken, $remember);

        //记录用户登录IP
        $login_log = new s_loginlog();
        $uid = $userToken->get_userid();
        $login_log->set_userid($uid);
        $outerip = get_request_ip();
        $login_log->set_login_ip($outerip);
        if (!str_equals($outerip, "") && !str_equals($outerip, "::1") && !str_equals($outerip, "127.0.0.1")) {
            $login_address = get_address($outerip);
            $login_log->set_login_address(unicode_decode($login_address));
        }
        $login_log->set_date_time('now');
        $login_log->insert($db);

        $result = $userToken->to_array();
        $result['code'] = 0;

        if ($login_type == 1) {
            $result['PublicKey'] = PUBLIC_KEY;
            $result['PushApiUrl'] = PUSH_API_URL;
            $result['UserToken'] = $result['token'];
            $result['EmployeeNo'] = $result['employee_no'];
            $user = get_employees()[$userToken->get_userid()];
            $dept = get_depts()[$user['dept1_id']];
            $result['DeptName'] = $dept['text'];
        }

        unset($result['password']);
        unset($result['token']);
        unset($result['session_magic_mark']);

        echo_result($result);
    }
    //修改资料
    if ($action == 2) {
//        $user = new M_User();
//        $user->set_field_from_array($userData);
//        $db = create_pdo();
//        $result = $user->update($db, true);
//        if (!$result[0]) die_error(USER_ERROR, '保存员工资料失败');
//        echo_msg('保存成功');
    }

    if ($action == 4) {
        $userid = request_int('userid');
        $old_pwd = request_string("old_pwd");
        $new_pwd = request_string("new_pwd");
        $userToken = new M_UserToken($userid);
        $db = create_pdo();
        $result = $userToken->load($db, $userToken);
        if (!$result[0]) die_error(USER_ERROR, "获取用户信息失败,请重稍后重试~");
        if (str_equals($userToken->get_password(), $new_pwd)) {
            die_error(USER_ERROR, "原始密码与星密码相同,请核对后重试~");
        } else if (str_equals($userToken->get_password(), $old_pwd)) {
            $userToken->set_password($new_pwd);
            $result = $userToken->update($db, true);
            if (!$result[0]) die_error(USER_ERROR, "密码修改失败,请重稍后重试~");
            echo_msg("密码修改成功,请重新登陆系统~");
        }else {
            die_error(USER_ERROR, "原始密码输入错误,请核对后重试~");
        }
    }
});

function get_address($ip) {
    $url = "http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=" . $ip;
    $html = file_get_contents($url);
    $country = cut_str($html, 'country":"', '","');
    $country = $country === false ? "" : $country;
    $province = cut_str($html, 'province":"', '","');
    $province = $province === false ? "" : $province;
    $city = cut_str($html, 'city":"', '","');
    $city = $city === false ? "" : $city;
    return $country . $province . $city;
}

function unicode_decode($name) {
    // 转换编码，将Unicode编码转换成可以浏览的utf-8编码
    $pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
    preg_match_all($pattern, $name, $matches);
    if (!empty($matches)) {
        $name = '';
        for ($j = 0; $j < count($matches[0]); $j++) {
            $str = $matches[0][$j];
            if (strpos($str, '\\u') === 0) {
                $code = base_convert(substr($str, 2, 2), 16, 10);
                $code2 = base_convert(substr($str, 4), 16, 10);
                $c = chr($code) . chr($code2);
                $c = iconv('UCS-2', 'UTF-8', $c);
                $name .= $c;
            } else {
                $name .= $str;
            }
        }
    }
    return $name;
}
