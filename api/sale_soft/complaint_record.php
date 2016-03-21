<?php

/**
 * 投诉记录
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/10/25
 */
use Models\Base\Model;
use Models\p_complaint_record_soft;
use Models\Base\SqlOperator;
use Models\Base\SqlSortType;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../Common/http.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');
        $complaint_record = new p_complaint_record_soft();
        if (isset($searchName)) {
            $complaint_record->set_custom_where(" AND (hand_personnel like '%" . $searchName . "%' OR ac_name like '%" . $searchName . "%'  OR ww like '%" . $searchName . "%' OR qq like '%" . $searchName . "%' OR phone like '%" . $searchName . "%') ");
        }
        if (isset($sort) && isset($sortname)) {
            $complaint_record->set_order_by($complaint_record->get_field_by_name($sortname), $sort);
        } else {
            $complaint_record->set_order_by(p_complaint_record_soft::$field_addtime, 'desc');
        }
        $complaint_record->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $complaint_record, NULL, true);
        if (!$result[0]) {
            die_error(USER_ERROR, '获取投诉记录失败，请重试');
        }
        $models = Model::list_to_array($result['models']);
        echo_list_result($result, $models);
    }
    if ($action == 11) {
        $export = new ExportData2Excel();
        $complaint_record = new p_complaint_record_soft();
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        if (isset($startTime)) {
            $complaint_record->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $complaint_record->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') <= '" . $endTime . "' ");
        }

        $field = array('addtime', 'ww', 'shop', 'qq', 'phone', 'complaint_custom', 'complaint_content', 'hand_personnel', 'hand_result', 'add_userid');
        $complaint_record->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $complaint_record, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('投诉记录导出失败,请稍后重试!')), "投诉记录导出", "投诉记录");
        }
        $employees = get_employees();
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($employees) {
                    $d['add_userid'] = $employees[$d['add_userid']]['username'];
                });
        $title_array = array('添加时间', '旺旺名', '所属店铺', 'QQ', '来电号码', '投诉客服', '投诉内容', '处理人员', '处理结果', '添加人员');
        $export->set_field($field);
        $export->create($title_array, $models, "投诉记录导出", "投诉记录");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $complaintRecordData = request_object();
    /**
     * 添加
     */
    if ($action == 1) {
        $complaint_record = new p_complaint_record_soft();
        $complaint_record->set_field_from_array($complaintRecordData);
        $complaint_record->set_addtime('now');
        $complaint_record->set_add_userid(request_login_userid());
        $db = create_pdo();
        $result = $complaint_record->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加投诉记录失败~');
        add_data_add_log($db, $complaintRecordData, $complaint_record, 12);
        echo_msg('添加投诉记录成功~');
    }

    /**
     * 删除
     */
    if ($action == 2) {
        $complaint_record = new p_complaint_record_soft($complaintRecordData->id);
        $db = create_pdo();
        $result = $complaint_record->delete($db);
        if (!$result[0]) die_error(USER_ERROR, '删除投诉记录失败~');
        echo_msg('删除投诉记录成功~');
    }

    /**
     * 修改
     */
    if ($action == 3) {
        $complaint_record = new p_complaint_record_soft($complaintRecordData->id);
        $complaint_record->set_field_from_array($complaintRecordData);
        $db = create_pdo();
        pdo_transaction($db, function($db) use($complaint_record, $complaintRecordData) {
            add_data_change_log($db, $complaintRecordData, $complaint_record, 12);
            $result = $complaint_record->update($db, TRUE);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '修改投诉记录失败~' . $result['detail_cn'], $result);
        });
        echo_msg('修改投诉记录成功~');
    }
});
