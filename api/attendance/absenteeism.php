<?php

/*
 * 旷工
 */

use Models\Base\Model;
use Models\Base\SqlSortType;
use Models\A_Absent;
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
        $absenteeism = new A_Absent();
        if (isset($searchDate)) {
            $absenteeism->set_where_and(A_Absent::$field_date, SqlOperator::Between, array($searchDate . ' 00.00.00', $searchDate . ' 23:59:59'));
        }
        if (isset($deptid)) {
            $absenteeism->set_where_and(A_Absent::$field_dept1_id, SqlOperator::Equals, $deptid);
            $absenteeism->set_where_or(A_Absent::$field_dept2_id, SqlOperator::Equals, $deptid);
        }
        if (isset($searchName)) {
            $absenteeism->set_where_and(A_Absent::$field_username, SqlOperator::Like, '%' . $searchName . '%');
        }
        if (isset($sort) && isset($sortname)) {
            $absenteeism->set_order_by($absenteeism->get_field_by_name($sortname), $sort);
        } else {
            $absenteeism->set_order_by(A_Absent::$field_date, SqlSortType::Desc);
        }
        get_record_by_role($absenteeism);
        $absenteeism->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $absenteeism, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取旷工资料失败，请重试');
        $models = Model::list_to_array($result['models'], array(), "id_2_text");
        echo_list_result($result, $models);
    }
    if ($action == 11) {
        $depts = get_depts();
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $absent = new A_Absent();
        if (isset($startTime)) {
            $absent->set_custom_where(" and DATE_FORMAT(date, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $absent->set_custom_where(" and DATE_FORMAT(date, '%Y-%m-%d') <= '" . $endTime . "' ");
        }
        $field = array('username', 'dept1_id', 'hours', 'date');
        $absent->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $absent, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('旷工数据导出失败,请稍后重试!')), "旷工数据导出", "旷工");
        }
        $models = Model::list_to_array($result['models'], array(), function (&$d) use($depts) {
                    $d['dept1_id'] = $depts[$d['dept1_id']]['text'];
                });
        $title_array = array('姓名', '所属部门', '矿工时长(小时)', '日期');
        $export->set_field($field);
        $export->set_field_width(array(8, 15, 15, 20));
        $export->create($title_array, $models, "旷工数据导出", "旷工");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    //添加
    if ($action == 1) {
        $absenteeismData = request_object();
        $absenteeism = new A_Absent();
        $absenteeism->set_field_from_array($absenteeismData);
        $user = get_employees()[request_int('userid')];
        $absenteeism->set_dept1_id($user['dept1_id']);
        $absenteeism->set_dept2_id($user['dept2_id']);
        $db = create_pdo();
        $result = $absenteeism->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加旷工信息失败~');
        echo_msg('添加旷工信息成功~');
    }
    //删除 
    if ($action == 2) {
        $absenteeismData = request_object();
        $absenteeism = new A_Absent($absenteeismData->id);
        $db = create_pdo();
        $result = $absenteeism->delete($db);
        if (!$result[0]) die_error(USER_ERROR, '删除旷工信息失败~');
        echo_msg('删除旷工信息成功~');
    }
});
