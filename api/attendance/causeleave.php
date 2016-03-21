<?php

/*
 * 事假
 */

use Models\Base\Model;
use Models\Base\SqlSortType;
use Models\A_Causeleave;
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
        $searchStartTime = request_string('searchStartTime');
        $searchEndTime = request_string('searchEndTime');
        $type = request_string("type");
        $causeleave = new A_Causeleave();
        if (isset($searchStartTime)) {
            $formatStr = '%Y-%m-%d';
            if (strlen($searchStartTime) > 10) {
                $formatStr = "%Y-%m-%d %H:%i";
            }
            $causeleave->set_custom_where(" AND DATE_FORMAT(starttime, '" . $formatStr . "') >= DATE_FORMAT('" . $searchStartTime . "','" . $formatStr . "') ");
        }
        if (isset($searchEndTime)) {
            $formatStr = '%Y-%m-%d';
            if (strlen($searchEndTime) > 10) {
                $formatStr = "%Y-%m-%d %H:%i";
            }
            $causeleave->set_custom_where(" AND DATE_FORMAT(starttime, '" . $formatStr . "') <= DATE_FORMAT('" . $searchEndTime . "','" . $formatStr . "') ");
        }
        if (isset($type) && $type != '0') {
            $causeleave->set_where_and(A_Causeleave::$field_type, SqlOperator::Equals, $type);
        }
        if (isset($deptid)) {
            $causeleave->set_where_and(A_Causeleave::$field_dept1_id, SqlOperator::Equals, $deptid);
        }
        if (isset($searchName)) {
            $causeleave->set_where_and(A_Causeleave::$field_username, SqlOperator::Like, '%' . $searchName . '%');
        }
        if (isset($sort) && isset($sortname)) {
            $causeleave->set_order_by($causeleave->get_field_by_name($sortname), $sort);
        } else {
            $causeleave->set_order_by(A_Causeleave::$field_status, SqlSortType::Asc);
            $causeleave->set_order_by(A_Causeleave::$field_date, SqlSortType::Desc);
            $causeleave->set_order_by(A_Causeleave::$field_userid, SqlSortType::Asc);
        }
        get_record_by_role($causeleave);
        $causeleave->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $causeleave, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取事假资料失败，请重试');
        $models = Model::list_to_array($result['models'], array(), "id_2_text");
        $roletype = get_role_type();
        array_walk($models, function(&$model) use($causeleave, $roletype) {
            $workflow_configs = get_causeleave_workflow($model);
            get_workflow($workflow_configs, $model, $roletype);
        });
        echo_list_result($result, $models);
    }
    if ($action == 11) {
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $sql = "SELECT a.userid,m.username,m.truename,a.dept1_id,a.type,a.reason,a.starttime,a.endtime,a.hours,a.salary FROM a_causeleave a INNER JOIN m_user m ON a.userid = m.userid ";
        if (isset($startTime)) {
            $sql.=" and DATE_FORMAT(a.date, '%Y-%m-%d') >= '" . $startTime . "' ";
        }
        if (isset($endTime)) {
            $sql.=" and DATE_FORMAT(a.date, '%Y-%m-%d') <= '" . $endTime . "' ";
        }
        $db = create_pdo();
        $result = Model::execute_custom_sql($db, $sql);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('请假数据导出失败,请稍后重试!')), "请假数据导出", "请假");
        }
        $types = array(1 => '事假', 2 => '病假', 3 => '产假', 4 => '丧假', 5 => '婚假', 6 => '陪产假');
        $depts = get_depts();
        $models = $result['results'];
        array_walk($models, function(&$d) use ($depts, $types) {
            $d['hours'] = floor($d['hours'] / 24) . "天" . floor($d['hours'] % 24) . "小时";
            $d['type'] = $types[$d['type']];
            $d['dept1_id'] = $depts[$d['dept1_id']]['text'];
        });
        $title_array = array('花名', '姓名', '所属部门', '假别', '原因', '开始时间', '结束时间', '时长(天)', '带薪假底薪');
        $export->set_field(array('username', 'truename', 'dept1_id', 'type', 'reason', 'starttime', 'endtime', 'hours', 'salary'));
        $export->set_field_width(array(10, 15, 12, 50, 20, 20, 20, 13));
        $export->create($title_array, $models, "请假数据导出", "请假");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $causeleaveData = request_object();
    $causeleave = new A_Causeleave($causeleaveData->id);
    if ($action == 1) {
        $causeleave->set_field_from_array($causeleaveData);
        $user = get_employees()[request_int('userid')];
        $causeleave->set_dept1_id($user['dept1_id']);
        $causeleave->set_dept2_id($user['dept2_id']);
        $days = time_diff_unit($causeleaveData->starttime, $causeleaveData->endtime);
        $causeleave->set_hours($days);
        $causeleave->set_date("now");
        $db = create_pdo();
        $result = $causeleave->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加事假信息失败~');
        echo_msg('添加事假信息成功~');
    }
    if ($action == 2) {
        $workflow_configs = get_causeleave_workflow((array) $causeleaveData);
        $db = create_pdo();
        set_workflow_status($db, $causeleaveData, $workflow_configs, $causeleave);
        $result = $causeleave->update($db);
        if (!$result[0]) die_error(USER_ERROR, '修改事假信息失败~');
        //PUSH_MESSAGE BEGIN
        send_workflow_msg($causeleaveData, $workflow_configs, $causeleave);
        //PUSH_MESSAGE END
        echo_msg('修改事假信息成功~');
    }
    if ($action == 3) {
        $causeData = request_object();
        $causeleave = new A_Causeleave();
        $causeleave->set_field_from_array($causeData);
        $db = create_pdo();
        $result = $causeleave->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除事假信息失败~');
        echo_msg('删除事假信息成功~');
    }
    if ($action == 4) {
        $causeleave->set_field_from_array($causeleaveData);
        $db = create_pdo();
        $days = time_diff_unit($causeleaveData->starttime, $causeleaveData->endtime);
        $causeleave->set_hours($days);
        $result = $causeleave->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '修改事假信息失败~');
        echo_msg('修改事假信息成功~');
    }
});
