<?php

/**
 * 调休
 */
use Models\Base\Model;
use Models\Base\SqlSortType;
use Models\A_AdjustRest;
use Models\W_WorkTable;
use Models\Base\SqlOperator;

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
        $adjustRest = new A_AdjustRest();
        if (isset($deptid)) {
            $adjustRest->set_where_and(A_AdjustRest::$field_dept1_id, SqlOperator::Equals, $deptid);
        }
        if (isset($searchName)) {
            $adjustRest->set_where_and(A_AdjustRest::$field_username, SqlOperator::Like, '%' . $searchName . '%');
        }
        if (isset($searchDate)) {
            $adjustRest->set_custom_where(" and ( DATE_FORMAT(rest_date,'%Y-%m-%d') = '" . $searchDate . "' or DATE_FORMAT(adjust_to,'%Y-%m-%d')  = '" . $searchDate . "' ) ");
        }

        if (isset($sort) && isset($sortname)) {
            $adjustRest->set_order_by($adjustRest->get_field_by_name($sortname), $sort);
        } else {
            $adjustRest->set_order_by(A_AdjustRest::$field_status, SqlSortType::Asc);
            $adjustRest->set_order_by(A_AdjustRest::$field_add_time, SqlSortType::Desc);
        }
        get_record_by_role($adjustRest);
        $adjustRest->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $adjustRest, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取调休单失败，请重试');
        $depts = get_depts();
        $employees = get_employees();
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($depts, $employees) {
                    $d['dept1_text'] = $depts[$d['dept1_id']]['text'];
                    $d['sex_txt'] = $employees[$d['userid']]['sex'] == '0' ? '女' : '男';
                });
        $roletype = get_role_type();
        array_walk($models, function(&$model) use($roletype) {
            $workflow_configs = get_adjustrest_workflow($model);
            get_workflow($workflow_configs, $model, $roletype);
        });
        echo_list_result($result, $models);
    }
    if ($action == 11) {
        $depts = get_depts();
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $adjustRest = new A_AdjustRest();
        if (isset($startTime)) {
            $adjustRest->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $adjustRest->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d') <= '" . $endTime . "' ");
        }
        $field = array('username', 'dept1_id', 'add_time', 'phone', 'rest_date', 'rest_days', 'adjust_to', 'adjust_days', 'reason');
        $adjustRest->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $adjustRest, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('调休数据导出失败,请稍后重试!')), "调休数据导出", "调休");
        }
        $models = Model::list_to_array($result['models'], array(), function (&$d) use($depts) {
                    $d['dept1_id'] = $depts[$d['dept1_id']]['text'];
                });
        $title_array = array('姓名', '所属部门', '填写日期', '联系方式', '原休息日期', '原休息天数', '调休至', '调休天数', '原因');
        $export->set_field($field);
        $export->set_field_width(array(8, 15, 12, 12, 20, 13, 20, 13, 40));
        $export->create($title_array, $models, "调休数据导出", "调休");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $adjustRestData = request_object();
    if ($action == 1) {
        $adjustRest = new A_AdjustRest();
        $adjustRest->set_field_from_array($adjustRestData);
        $user = get_employees()[request_int('userid')];
        $adjustRest->set_dept1_id($user['dept1_id']);
        $adjustRest->set_add_time('now');
        $db = create_pdo();
        $result = $adjustRest->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加调休申请失败~');
        echo_msg('添加调休申请成功~');
    }
    if ($action == 2) {
        $adjustRest = new A_AdjustRest();
        $adjustRest->set_field_from_array($adjustRestData);
        $workflow_configs = get_adjustRest_workflow((array) $adjustRestData);
        $db = create_pdo();
        set_workflow_status($db, $adjustRestData, $workflow_configs, $adjustRest);
        if ($adjustRestData->status == 6) {
            $rest_date = date("Y-m", strtotime($adjustRestData->rest_date)); //原休息日期
            $adjust_to = date("Y-m", strtotime($adjustRestData->adjust_to)); //调休日期
            $work_table = new W_WorkTable();
            $work_table->set_where_and(W_WorkTable::$field_userid, SqlOperator::Equals, $adjustRestData->userid);
            $work_table->set_custom_where(" AND DATE_FORMAT(date,'%Y-%m') IN ('" . $rest_date . "','" . $adjust_to . "') ");
            $work_table_result = Model::query_list($db, $work_table);
            if (!$work_table_result[0]) die_error(USER_ERROR, '保存调休申请单失败~');
            $work_table_models = Model::list_to_array($work_table_result['models']);
            if ($work_table_result['count'] == 2) {
                $work_table = $work_table_models[0];
                $work_table1 = $work_table_models[1];
                $sec_d = $work_table['m' . $rest_date];
                $work_table['m' . $rest_date] = $work_table1['m' . $adjust_to];
                $work_table1['m' . $adjust_to] = $sec_d;
                pdo_transaction($db, function($db) use($adjustRest, $work_table, $work_table1) {
                    $adjustRest_res = $adjustRest->update($db, true);
                    if (!$adjustRest_res[0]) throw new TransactionException(PDO_ERROR_CODE, '保存调休申请单失败~' . $adjustRest_res['detail_cn'], $adjustRest_res);
                    $work_table_model = new W_WorkTable($work_table['id']);
                    $work_table_model->set_field_from_array($work_table);
                    $work_table_res = $work_table_model->update($db, true);
                    if (!$work_table_res[0]) throw new TransactionException(PDO_ERROR_CODE, '保存调休申请单失败~' . $work_table_res['detail_cn'], $work_table_res);
                    $work_table_model->reset();
                    $work_table_model = new W_WorkTable($work_table1['id']);
                    $work_table_model->set_field_from_array($work_table);
                    $work_table_res = $work_table_model->update($db, true);
                    if (!$work_table_res[0]) throw new TransactionException(PDO_ERROR_CODE, '保存调休申请单失败~' . $work_table_res['detail_cn'], $work_table_res);
                });
            } else if ($work_table_result['count'] == 1) {
                $rest_date = date("d", strtotime($adjustRestData->rest_date));
                $adjust_to = date("d", strtotime($adjustRestData->adjust_to));
                $work_table_models = $work_table_models[0];
                $sec_d = $work_table_models['m' . $rest_date];
                $work_table_models['m' . $rest_date] = $work_table_models['m' . $adjust_to];
                $work_table_models['m' . $adjust_to] = $sec_d;
                $work_table->reset();
                $work_table->set_field_from_array($work_table_models);
                pdo_transaction($db, function($db) use($adjustRest, $work_table) {
                    $adjustRest_res = $adjustRest->update($db, true);
                    if (!$adjustRest_res[0]) throw new TransactionException(PDO_ERROR_CODE, '保存调休申请单失败~' . $adjustRest_res['detail_cn'], $adjustRest_res);
                    $work_table_res = $work_table->update($db, true);
                    if (!$work_table_res[0]) throw new TransactionException(PDO_ERROR_CODE, '保存调休申请单失败~' . $work_table_res['detail_cn'], $work_table_res);
                });
            }
        } else {
            $result = $adjustRest->update($db, true);
            if (!$result[0]) die_error(USER_ERROR, '保存调休申请单失败~');
        }
//        //PUSH_MESSAGE BEGIN
        send_workflow_msg($adjustRestData, $workflow_configs, $adjustRest);
        //PUSH_MESSAGE END
        echo_msg('保存调休申请单成功~');
    }
    if ($action == 3) {
        $adjustRest = new A_AdjustRest($adjustRestData->id);
        $db = create_pdo();
        $result = $adjustRest->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除调休申请失败~');
        echo_msg('删除调休申请成功~');
    }
    if ($action == 4) {
        $adjustRest = new A_AdjustRest();
        $adjustRest->set_field_from_array($adjustRestData);
        $db = create_pdo();
        $result = $adjustRest->update($db);
        if (!$result[0]) die_error(USER_ERROR, '保存失败~');
        echo_msg('保存成功~');
    }
});
