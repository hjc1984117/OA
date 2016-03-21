<?php

/*
 * 补卡(补签)
 */

use Models\Base\Model;
use Models\Base\SqlSortType;
use Models\A_Resign;
use Models\Base\SqlOperator;
use Common\ExtDateTime;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../common/http.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $deptid = request_int('deptid');
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');
        $searchDate = request_string('searchDate');
        $resign = new A_Resign();
        if (isset($searchDate)) {
            $resign->set_where_and(A_Resign::$field_signdate, SqlOperator::Between, array($searchDate . ' 00.00.00', $searchDate . ' 23:59:59'));
        }
        if (isset($deptid)) {
            $resign->set_where_and(A_Resign::$field_dept1_id, SqlOperator::Equals, $deptid);
            $resign->set_where_or(A_Resign::$field_dept2_id, SqlOperator::Equals, $deptid);
        }
        if (isset($searchName)) {
            $resign->set_where_and(A_Resign::$field_username, SqlOperator::Like, '%' . $searchName . '%');
        }
        if (isset($sort) && isset($sortname)) {
            $resign->set_order_by($resign->get_field_by_name($sortname), $sort);
        } else {
            $resign->set_order_by(A_Resign::$field_status, SqlSortType::Asc);
            $resign->set_order_by(A_Resign::$field_addtime, SqlSortType::Desc);
        }
        get_record_by_role($resign);
        $resign->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $resign, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取补卡(补签)资料失败，请重试');
        $models = Model::list_to_array($result['models'], array(), "id_2_text");
        $roletype = get_role_type();
        array_walk($models, function(&$model)use($roletype) {
            $workflow_configs = get_resign_workflow($model);
            get_workflow($workflow_configs, $model, $roletype);
        });
        echo_list_result($result, $models);
    }
    if ($action == 11) {
        $depts = get_depts();
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $resign = new A_Resign();
        if (isset($startTime)) {
            $resign->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $resign->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') <= '" . $endTime . "' ");
        }
        $field = array('username', 'dept1_id', 'reason', 'signtype', 'signdate');
        $resign->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $resign, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('补卡(补签)数据导出失败,请稍后重试!')), "补卡(补签)数据导出", "补卡(补签)");
        }
        $reasons = array(0 => '签到', 1 => '签退');
        $models = Model::list_to_array($result['models'], array(), function (&$d) use($depts, $reasons) {
                    $d['signtype'] = $reasons[$d['signtype']];
                    $d['dept1_id'] = $depts[$d['dept1_id']]['text'];
                });
        $title_array = array('姓名', '所属部门', '原因', '补签类型', '补签时间');
        $export->set_field($field);
        $export->set_field_width(array(8, 15, 50, 15, 20));
        $export->create($title_array, $models, "补卡(补签)数据导出", "补卡(补签)");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    if ($action == 1) {
        $resignData = request_object();
        $resign = new A_Resign();
        $resign->set_field_from_array($resignData);
        $user = get_employees()[request_int('userid')];
        $resign->set_dept1_id($user['dept1_id']);
        $resign->set_dept2_id($user['dept2_id']);
        $resign->set_addtime('now');
        //$resign->set_status(1);
        $db = create_pdo();
        $result = $resign->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加补卡(补签)信息失败~');
        echo_msg('添加补卡(补签)信息成功~');
    }
    if ($action == 2) {
        $resignData = request_object();
        $resign = new A_Resign();
        $resign->set_field_from_array($resignData);
        $workflow_configs = get_resign_workflow((array) $resignData);
        $db = create_pdo();
        set_workflow_status($db, $resignData, $workflow_configs, $resign);
        $result = $resign->update($db);
        if (!$result[0]) die_error(USER_ERROR, '修改补卡(补签)信息失败~');
        //PUSH_MESSAGE BEGIN
        send_workflow_msg($resignData, $workflow_configs, $resign);
        //PUSH_MESSAGE END
        echo_msg('修改补卡(补签)信息成功~');
    }
    if ($action == 3) {
        $resignData = request_object();
        $resign = new A_Resign();
        $resign->set_field_from_array($resignData);
        $db = create_pdo();
        $result = $resign->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '修改补卡(补签)信息失败~');
        echo_msg('修改补卡(补签)信息成功~');
    }
    if ($action == 4) {
        $resignData = request_object();
        $resign = new A_Resign($resignData->id);
        $db = create_pdo();
        $result = $resign->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除补卡(补签)信息失败~');
        echo_msg('删除补卡(补签)信息成功~');
    }
});
