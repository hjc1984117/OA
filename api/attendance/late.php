<?php

/*
 * 迟到
 */

use Models\Base\Model;
use Models\Base\SqlSortType;
use Models\A_Late;
use Models\Base\SqlOperator;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $deptid = request_int('deptid');
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');
        $searchDate = request_string('searchDate');
        $late = new A_Late();
        if (isset($searchDate)) {
            $late->set_where_and(A_Late::$field_date, SqlOperator::Between, array($searchDate . ' 00.00.00', $searchDate . ' 23:59:59'));
        }
        if (isset($deptid)) {
            $late->set_where_and(A_Late::$field_dept1_id, SqlOperator::Equals, $deptid);
        }
        if (isset($searchName)) {
            $late->set_where_and(A_Late::$field_username, SqlOperator::Like, '%' . $searchName . '%');
        }
        if (isset($sort) && isset($sortname)) {
            $late->set_order_by($late->get_field_by_name($sortname), $sort);
        } else {
            $late->set_order_by(A_Late::$field_date, SqlSortType::Desc);
        }
        get_record_by_role($late);
        $late->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $late, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取迟到资料失败，请重试');
        $models = Model::list_to_array($result['models'], array(), "id_2_text");
        echo_list_result($result, $models);
    }
    if ($action == 11) {
        $depts = get_depts();
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $late = new A_Late();
        if (isset($startTime)) {
            $late->set_custom_where(" and DATE_FORMAT(date, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $late->set_custom_where(" and DATE_FORMAT(date, '%Y-%m-%d') <= '" . $endTime . "' ");
        }
        $field = array('username', 'dept1_id', 'mins', 'date');
        $late->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $late, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('迟到数据导出失败,请稍后重试!')), "迟到数据导出", "迟到");
        }
        $models = Model::list_to_array($result['models'], array(), function (&$d) use($depts) {
                    $d['dept1_id'] = $depts[$d['dept1_id']]['text'];
                });
        $title_array = array('姓名', '所属部门', '迟到时长(分钟)', '日期');
        $export->set_field($field);
        $export->set_field_width(array(8, 15, 15, 20));
        $export->create($title_array, $models, "迟到数据导出", "迟到");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    if ($action == 1) {
        $lateData = request_object();
        $late = new A_Late();
        $late->set_field_from_array($lateData);
        $user = get_employees()[request_int('userid')];
        $late->set_dept1_id($user['dept1_id']);
        $late->set_dept2_id($user['dept2_id']);
        $db = create_pdo();
        $result = $late->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加迟到信息失败~');
        echo_msg('添加迟到信息成功~');
    }
    if ($action == 2) {
        $lateData = request_object();
        $late = new A_Late();
        $late->set_field_from_array($lateData);
        $employee = get_employees()[$lateData->userid];
        $late->set_dept1_id($employee['dept1_id']);
        $late->set_dept2_id($employee['dept2_id']);
        $db = create_pdo();
        $result = $late->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '修改迟到信息失败~');
        echo_msg('修改迟到信息成功~');
    }
    if ($action == 3) {
        $lateData = request_object();
        $late = new A_Late();
        $late->set_field_from_array($lateData);
        $db = create_pdo();
        $result = $late->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除迟到信息失败~');
        echo_msg('删除迟到信息成功~');
    }
});
