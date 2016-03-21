<?php

/**
 * 投诉记录
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/10/25
 */
use Models\Base\Model;
use Models\p_complaint_record;
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
        $searchSource = request_string('searchSource');
        $searchPerson = request_string('searchPerson');
        $searchResult = request_string('searchResult');
        $complaint_record = new p_complaint_record();
        if (isset($searchName)) {
            $complaint_record->set_custom_where(" AND (hand_personnel like '%" . $searchName . "%' OR ac_name like '%" . $searchName . "%'  OR ww like '%" . $searchName . "%' OR qq like '%" . $searchName . "%' OR phone like '%" . $searchName . "%') ");
        }
        if (isset($searchSource)) {
            $complaint_record->set_where_and(p_complaint_record::$field_complaint_source, SqlOperator::Equals, $searchSource);
        }
        if (isset($searchPerson)) {
            if (str_equals($searchPerson, "1")) {
                $complaint_record->set_custom_where(" AND hand_personnel != '' ");
            } else {
                $complaint_record->set_custom_where(" AND hand_personnel ='' ");
            }
        }
        if (isset($searchResult)) {
            if (str_equals($searchResult, "1")) {
                $complaint_record->set_custom_where(" AND (hand_result != '' OR hand_result IS NOT NULL ) ");
            } else {
                $complaint_record->set_custom_where(" AND hand_result ='' OR hand_result IS NULL ");
            }
        }
        if (isset($sort) && isset($sortname)) {
            $complaint_record->set_order_by($complaint_record->get_field_by_name($sortname), $sort);
        } else {
            $complaint_record->set_order_by(p_complaint_record::$field_addtime, 'desc');
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
    /**
     * 投诉记录处理人员
     */
    if ($action == 10) {
        $user_array = array();
        array_push($user_array, array('id' => '', 'text' => '无'));
        foreach (get_employees() as $v) {//退款部,调研部__孔明,一灯,柳玉茹,戴笠
            if ((in_array($v['dept1_id'], array(10, 17)) || in_array($v['userid'], array(35, 37, 125, 137))) && ((strpos($v['username'], '测试用户') === false))) {
                array_push($user_array, array('id' => $v['userid'], 'text' => $v['username']));
            }
        }
        echo_result($user_array);
    }
    if ($action == 11) {
        $export = new ExportData2Excel();
        $complaint_record = new p_complaint_record();
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        if (isset($startTime)) {
            $complaint_record->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $complaint_record->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') <= '" . $endTime . "' ");
        }

        $field = array('addtime', 'ww', 'shop', 'qq', 'phone', 'complaint_custom', 'complaint_content', 'hand_personnel', 'hand_result', 'add_userid', 'remark');
        $complaint_record->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $complaint_record, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('投诉记录导出失败,请稍后重试!')), "投诉记录导出", "投诉记录");
        }
        $employees = get_employees();
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($employees) {
                    $d['add_userid'] = $employees[$d['add_userid']]['username'];
                    $complaint_content = json_decode($d['complaint_content']);
                    if ($complaint_content !== null) {
                        $d['complaint_content'] = "";
                        $d['hand_result'] = "";
                        foreach ($complaint_content as $k => $v) {
                            $v = (array) $v;
                            $d['complaint_content'].= "(" . ($k + 1) . ")." . $v['complaint_content'] . chr(10);
                            $hand_result = (array) $v['hand_result'];
                            foreach ($hand_result as $key => $value) {
                                $value = (array) $value;
                                $d['hand_result'] .= "(" . ($k + 1) . "-" . ($key + 1) . ")." . $value['text'] . chr(10);
                            }
                        }
                    }
                });
        $title_array = array('添加时间', '旺旺名', '所属店铺', 'QQ', '来电号码', '投诉客服', '投诉内容', '处理人员', '处理结果', '添加人员', '备注');
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
        $complaint_record = new p_complaint_record();
        $complaint_record->set_field_from_array($complaintRecordData);
        $complaint_record->set_addtime('now');
        $complaint_record->set_add_userid(request_login_userid());
        $db = create_pdo();
        $result = $complaint_record->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加投诉记录失败~');
        add_data_add_log($db, $complaintRecordData, $complaint_record, 11);
        echo_msg('添加投诉记录成功~');
    }

    /**
     * 删除
     */
    if ($action == 2) {
        $complaint_record = new p_complaint_record($complaintRecordData->id);
        $db = create_pdo();
        $result = $complaint_record->delete($db);
        if (!$result[0]) die_error(USER_ERROR, '删除投诉记录失败~');
        echo_msg('删除投诉记录成功~');
    }

    /**
     * 修改
     */
    if ($action == 3) {
        $complaint_record = new p_complaint_record($complaintRecordData->id);
        $complaint_record->set_field_from_array($complaintRecordData);
        $db = create_pdo();
        pdo_transaction($db, function($db) use($complaint_record, $complaintRecordData) {
            add_data_change_log($db, $complaintRecordData, $complaint_record, 11);
            $result = $complaint_record->update($db, TRUE);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '修改投诉记录失败~' . $result['detail_cn'], $result);
        });
        echo_msg('修改投诉记录成功~');
    }
});
