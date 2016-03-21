<?php

/*
 * 停薪留职
 */

use Models\Base\Model;
use Models\Base\SqlSortType;
use Models\A_UnPaidWork;
use Models\Base\SqlOperator;
use Common\ExtDateTime;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $deptid = request_int('deptid');
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string("searchName");
    $searchDate = request_string('searchDate');
    $unpaidwork = new A_UnPaidWork();
    if(isset($searchDate)){
        $unpaidwork->set_where_and(A_UnPaidWork::$field_starttime, SqlOperator::Between, array($searchDate . ' 00.00.00', $searchDate . ' 23:59:59'));
    }
    if (isset($deptid)) {
        $unpaidwork->set_where_and(A_UnPaidWork::$field_dept1_id, SqlOperator::Equals, $deptid);
        $unpaidwork->set_where_or(A_UnPaidWork::$field_dept2_id, SqlOperator::Equals, $deptid);
    }
    if (isset($searchName)) {
        $unpaidwork->set_where_and(A_UnPaidWork::$field_username, SqlOperator::Like, "%" . $searchName . "%");
    }
    if (isset($sort) && isset($sortname)) {
        $unpaidwork->set_order_by($unpaidwork->get_field_by_name($sortname), $sort);
    } else {
        $unpaidwork->set_order_by(A_UnPaidWork::$field_starttime, SqlSortType::Desc);
    }
    get_record_by_role($unpaidwork);
    $unpaidwork->set_limit_paged(request_pageno(), request_pagesize());
    $db = create_pdo();
    $result = Model::query_list($db, $unpaidwork, NULL, true);
    if (!$result[0]) die_error(USER_ERROR, '获取停薪留职资料失败，请重试');
    $roles = get_roles();
    $depts = get_depts();
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $unpaidworkData = request_object();
    $unpaidwork = new A_UnPaidWork($unpaidworkData->id);
    $unpaidwork->set_field_from_array($unpaidworkData);
    if ($action == 1) {
        $user = get_employees()[request_int('userid')];
        $unpaidwork->set_dept1_id($user['dept1_id']);
        $unpaidwork->set_dept2_id($user['dept2_id']);
        $hours = time_diff_unit($unpaidworkData->starttime, $unpaidworkData->endtime);
        $unpaidwork->set_hours($hours);
        $unpaidwork->set_join_time($user['join_time']);
        $unpaidwork->set_role_id($user['role_id']);
        $db = create_pdo();
        $result = $unpaidwork->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加停薪留职信息失败~');
        echo_msg('添加停薪留职信息成功~');
    }
    if ($action == 2) {
        $db = create_pdo();
        $emplor = get_employees()[$unpaidworkData->userid];
        $unpaidwork->set_dept1_id($emplor["dept1_id"]);
        $unpaidwork->set_role_id($emplor["role_id"]);
        $unpaidwork->set_join_time($emplor["join_time"]);
        
        $result = $unpaidwork->update($db,true);
        if (!$result[0]) die_error(USER_ERROR, '修改停薪留职信息失败~');
        echo_msg('修改停薪留职信息成功~');
    }
    if ($action == 3) {
        $db = create_pdo();
        $result = $unpaidwork->delete($db, true);
        if (!$result[0])
            die_error(USER_ERROR, '删除停薪留职信息失败~');
        echo_msg('删除停薪留职信息成功~');
    }
});
