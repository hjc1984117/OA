<?php

/*
 * 带薪假
 */

use Models\Base\Model;
use Models\Base\SqlSortType;
use Models\A_PaidLeave;
use Models\Base\SqlOperator;
use Common\ExtDateTime;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $deptid = request_int('deptid');
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    $paidLeave = new A_PaidLeave();
    if (isset($deptid)) {
        $paidLeave->set_where_and(A_PaidLeave::$field_dept1_id, SqlOperator::Equals, $deptid);
        $paidLeave->set_where_or(A_PaidLeave::$field_dept2_id, SqlOperator::Equals, $deptid);
    }
    if (isset($searcherName)) {
        $paidLeave->set_where_and(A_PaidLeave::$field_username, SqlOperator::Like, '%' . $searcherName . '%');
    }
    if (isset($sort) && isset($sortname)) {
        $paidLeave->set_order_by($paidLeave->get_field_by_name($sortname), $sort);
    } else {
        $paidLeave->set_order_by(A_PaidLeave::$field_starttime, SqlSortType::Desc);
    }
    get_record_by_role($paidLeave);
    $paidLeave->set_limit_paged(request_pageno(), request_pagesize());
    $db = create_pdo();
    $result = Model::query_list($db, $paidLeave, NULL, true);
    if (!$result[0]) die_error(USER_ERROR, '获取带薪假资料失败，请重试');
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $paidLeaveData = request_object();
    $paidLeave = new A_PaidLeave();
    $paidLeave->set_field_from_array($paidLeaveData);
    if ($action == 1) {
        $user = get_employees()[request_int('userid')];
        $paidLeave->set_dept1_id($user['dept1_id']);
        $paidLeave->set_dept2_id($user['dept2_id']);
        $days = time_diff_unit($paidLeaveData->starttime, $paidLeaveData->endtime);
        $paidLeave->set_hours($days);
        $db = create_pdo();
        $result = $paidLeave->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加带薪假信息失败~');
        echo_msg('添加带薪假信息成功~');
    }
    if ($action == 2) {
        $db = create_pdo();
        $result = $paidLeave->update($db);
        if (!$result[0]) die_error(USER_ERROR, '修改薪假信息失败~');
        echo_msg('修改带薪假信息成功~');
    }
});
