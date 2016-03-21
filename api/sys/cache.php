<?php

/**
 * 缓存数据
 *
 * @author ChenHao
 * @copyright 2015 星密码
 * @version 2015/1/26
 */
require '../../application.php';
require '../../loader-api.php';

check_login();

$action = request_action();

if ($action == 1) {
    $dept1_id = request_int('dept1_id');
    $dept2_id = request_int('dept2_id');
    $result = get_employees();

    $result = array_filter($result, function($item) {
        return !str_equals($item['username'], 'admin') && $item['status'] > -1;
    });
    if ($dept1_id > 0) {
        $result = array_filter($result, function($item) use($dept1_id) {
            return $item['dept1_id'] == $dept1_id;
        });
    }
    if ($dept2_id > 0) {
        $result = array_filter($result, function($item) use($dept2_id) {
            return $item['dept2_id'] == $dept2_id;
        });
    }

    array_sort_by_field($result, 'username');

    $emps = array(
        'A-E' => array(),
        'F-J' => array(),
        'K-O' => array(),
        'P-T' => array(),
        'U-Z' => array());
    foreach ($result as $item) {
        $emps[$item['py_group']][] = $item;
    }
    echo_result($emps);
}
if ($action == 2) {
    $dept1_id = request_int('dept1_id');
    $dept2_id = request_int('dept2_id');
    $result = get_roles();
    $dept = get_depts();
    array_walk($result, function(&$item) use ($dept) {
        $item['dept1_text'] = $dept[$item['dept1_id']]['text'];
        $item['dept2_text'] = $dept[$item['dept2_id']]['text'];
    });
    if ($dept1_id > 0) {
        $result = array_filter($result, function($item) use($dept1_id) {
            return $item['dept1_id'] == $dept1_id;
        });
    }
    if ($dept2_id > 0) {
        $result = array_filter($result, function($item) use($dept2_id) {
            return $item['dept2_id'] == $dept2_id;
        });
    }
    echo_result($result);
}

if ($action == 3) {
    $dept1_id = request_int('dept1_id');
    $dept2_id = request_int('dept2_id');
    $users_array = get_employees();
    $res_users = array_filter($users_array, function($user) use($dept1_id, $dept2_id) {
        $is_not_test = (strpos($user['username'], '测试用户') === false);
        $is_not_admin = (!str_equals($user['username'], 'admin'));
        return ($is_not_test && $is_not_admin && ($dept1_id > 0 && $user['dept1_id'] == $dept1_id) || ($dept2_id > 0 && $user['dept2_id'] == $dept2_id));
    });
    echo_result($res_users);
}