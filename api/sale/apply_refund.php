<?php

/**
 * 退款记录
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/11/18
 */
use Models\Base\Model;
use Models\p_refundapply;
use Models\Base\SqlOperator;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../Common/http.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string("searchName");
        $searchDateTime = request_string("searchDateTime");
        $searchAutoStatus = request_string("searchAutoStatus");
        $rstatus = request_string('rstatus');
        $estatus = request_string('estatus');
        $refund = new p_refundapply();
        if (isset($searchName)) {
            $refund->set_custom_where(" AND (name LIKE '%" . $searchName . "%' OR ww LIKE '%" . $searchName . "%' OR qq LIKE '%" . $searchName . "%' OR mobile LIKE '%" . $searchName . "%' OR done_username LIKE '%" . $searchName . "%' OR customer LIKE '%" . $searchName . "%' OR nick_name LIKE '%" . $searchName . "%' )");
        }
        if (isset($searchDateTime)) {
            $refund->set_custom_where(" AND (DATE_FORMAT(int_time,'%Y-%m-%d') = '" . $searchDateTime . "' OR DATE_FORMAT(addtime,'%Y-%m-%d') = '" . $searchDateTime . "'  )");
        }
        if (isset($searchAutoStatus)) {
            if (str_equals($searchAutoStatus, "介入")) {
                $refund->set_where_and(p_refundapply::$field_done_userid, SqlOperator::NotEquals, 0);
            } else {
                $refund->set_where_and(p_refundapply::$field_done_userid, SqlOperator::Equals, 0);
            }
        }
        if (isset($estatus)) {
            if (str_equals($estatus, "处理")) {
                $refund->set_where_and(p_refundapply::$field_end_time, SqlOperator::IsNotNull);
            } else {
                $refund->set_where_and(p_refundapply::$field_end_time, SqlOperator::IsNull);
            }
        }
        if (isset($rstatus)) {
            $refund->set_where_and(p_refundapply::$field_rstatus, SqlOperator::Equals, $rstatus);
        }
        if (isset($sort) && isset($sortname)) {
            $refund->set_order_by($refund->get_field_by_name($sortname), $sort);
        } else {
            $refund->set_order_by(p_refundapply::$field_id, 'desc');
        }

        $refund->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $refund, NULL, true);
        if (!$result[0]) {
            die_error(USER_ERROR, '获取退款申请信息失败，请重试');
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $d['addtime'] = str_equals($d['addtime'], "") ? "" : date("Y-m-d", strtotime($d['addtime']));
                    $d['apply_time'] = str_equals($d['apply_time'], "") ? "" : date("Y-m-d", strtotime($d['apply_time']));
                    $d['sale_addtime'] = str_equals($d['sale_addtime'], "") ? "" : date("Y-m-d", strtotime($d['sale_addtime']));
                    $d['end_time'] = str_equals($d['end_time'], "") ? "" : date("Y-m-d", strtotime($d['end_time']));
                    $d['delay_time'] = str_equals($d['delay_time'], "") ? "" : date("Y-m-d", strtotime($d['delay_time']));
                    $d['int_time'] = str_equals($d['int_time'], "") ? "" : date("Y-m-d", strtotime($d['int_time']));
                });
        $user_array = array();
        $employees = get_employees();
        array_walk($employees, function($d) use(&$user_array) {
            if (in_array($d['dept1_id'], array(6, 17)) && !str_equals(substr($d['username'], -1), ")")) {
                array_push($user_array, array('id' => $d['userid'], 'text' => $d['username']));
            }
        });
        echo_list_result($result, $models, array('user_list' => $user_array));
    }
    if ($action == 11) {
        $type = request_int("type");
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $refund = new p_refundapply();
        if ($type === 1) {
            if (isset($startTime)) {
                $refund->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') >= '" . $startTime . "'");
            }
            if (isset($endTime)) {
                $refund->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') <= '" . $endTime . "' ");
            }
        } else {
            if (isset($startTime)) {
                $refund->set_custom_where(" and DATE_FORMAT(int_time, '%Y-%m-%d') >= '" . $startTime . "'");
            }
            if (isset($endTime)) {
                $refund->set_custom_where(" and DATE_FORMAT(int_time, '%Y-%m-%d') <= '" . $endTime . "' ");
            }
        }
        $field = array('apply_time', 'rstatus', 'reason', 'name', 'ww', 'qq', 'mobile', 'money', 'arrears', 'payment', 'customer', 'int_time', 'end_time', 'done_username');
        $refund->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $refund);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('退款申请数据导出失败,请稍后重试!')), "退款申请数据导出", "退款申请");
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $d['addtime'] = str_equals($d['addtime'], "") ? "" : date("Y-m-d", strtotime($d['addtime']));
                    $d['apply_time'] = str_equals($d['apply_time'], "") ? "" : date("Y-m-d", strtotime($d['apply_time']));
                    $d['sale_addtime'] = str_equals($d['sale_addtime'], "") ? "" : date("Y-m-d", strtotime($d['sale_addtime']));
                    $d['end_time'] = str_equals($d['end_time'], "") ? "" : date("Y-m-d", strtotime($d['end_time']));
                    $d['delay_time'] = str_equals($d['delay_time'], "") ? "" : date("Y-m-d", strtotime($d['delay_time']));
                    $d['int_time'] = str_equals($d['int_time'], "") ? "" : date("Y-m-d", strtotime($d['int_time']));
                });
        $title_array = array('申请日期', '申请方式', '申请原因', '客户姓名', '旺旺', 'QQ', '电话', '销售金额', '欠款', '所属店铺', '所属客服', '介入时间', '完成时间', '处理人员');
        $export->set_field($field);
        $export->create($title_array, $models, "退款申请数据导出", "退款申请数据");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $refundData = request_object();
    //添加退款申请
    if ($action == 1) {
        $refund = new p_refundapply();
        $refund->set_field_from_array($refundData);
        $refundarray = (array) $refundData;
        if ($refundarray['sale_addtime'] === "") {
            $refund->set_sale_addtime('now');
        }
        if (!isset($refundarray['apply_time']) || $refundarray['apply_time'] === "") {
            $refund->set_apply_time("now");
        }
        if ($refundarray['done_userid'] !== "") {
            $refund->set_int_time('now');
        }
        $refund->set_addtime('now');
        $db = create_pdo();
        $result = $refund->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加退款失败。');
        echo_msg('添加成功');
    }
    //修改退款
    if ($action == 2) {
        $refund = new p_refundapply();
        $refund->set_field_from_array($refundData);
        $db = create_pdo();
        $result = $refund->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存退款资料失败');
        echo_msg('保存成功');
    }
    //删除退款
    if ($action == 3) {
        $refund = new p_refundapply();
        $refund->set_field_from_array($refundData);
        $db = create_pdo();
        $result = $refund->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除失败');
        echo_msg('删除成功');
    }
    /**
     * 退款介入
     */
    if ($action == 4) {
        $rid = request_string('rid');
        $done_userid = request_int('done');
        if (!isset($rid)) die_error(USER_ERROR, '退款介入失败,请稍后重试~');
        $rid = explode(",", $rid);
        $refund_array = array();
        foreach ($rid as $v) {
            $refund = new p_refundapply();
            $refund->set_int_time('now');
            $refund->set_done_userid($done_userid);
            $employee = get_employees()[$done_userid];
            $refund->set_done_username($employee['username']);
            $refund->set_where_and(p_refundapply::$field_id, SqlOperator::Equals, (int) $v);
            array_push($refund_array, $refund);
        }
        $db = create_pdo();
        pdo_transaction($db, function($db) use ($refund_array) {
            foreach ($refund_array as $refund) {
                $result = $refund->update($db);
                if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '退款介入失败,请稍后重试~' . $result['detail_cn'], $result);
            }
        });
        echo_code(0);
    }
    if ($action == 5) {
        $rid = request_int("rid");
        $remark = request_string("remark");
        $refund = new p_refundapply();
        $refund->set_end_time('now');
        $refund->set_remark($remark);
        $refund->set_where_and(p_refundapply::$field_id, SqlOperator::Equals, $rid);
        $db = create_pdo();
        $result = $refund->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '完成退款申请失败,请稍后重试~');
        echo_code(0);
    }
});

