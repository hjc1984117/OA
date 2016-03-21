<?php

/**
 * 退款记录
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/11/18
 */
use Models\Base\Model;
use Models\p_refundapply_soft;
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
        $rstatus = request_int('rstatus');
        $estatus = request_string('estatus');
        $login_userid = request_login_userid();
        $is_manager = is_manager($login_userid, 2);
        $refund = new p_refundapply_soft();
        if (isset($searchName)) {
            $refund->set_custom_where(" AND (name LIKE '%" . $searchName . "%' OR ww LIKE '%" . $searchName . "%' OR qq LIKE '%" . $searchName . "%' OR mobile LIKE '%" . $searchName . "%' OR done_username LIKE '%" . $searchName . "%' OR customer LIKE '%" . $searchName . "%' OR nick_name LIKE '%" . $searchName . "%' )");
        }
        if (isset($searchDateTime)) {
            $refund->set_custom_where(" AND DATE_FORMAT(int_time,'%Y-%m-%d') = '" . $searchDateTime . "' ");
        }
        if (isset($searchAutoStatus)) {
            if (str_equals($searchAutoStatus, "介入")) {
                $refund->set_where_and(p_refundapply_soft::$field_done_userid, SqlOperator::NotEquals, 0);
            } else {
                $refund->set_where_and(p_refundapply_soft::$field_done_userid, SqlOperator::Equals, 0);
            }
        }
        if (isset($estatus)) {
            if (str_equals($estatus, "处理")) {
                $refund->set_where_and(p_refundapply_soft::$field_end_time, SqlOperator::IsNotNull);
            } else {
                $refund->set_where_and(p_refundapply_soft::$field_end_time, SqlOperator::IsNull);
            }
        }
        if (isset($rstatus)) {
            $refund->set_where_and(p_refundapply_soft::$field_rstatus, SqlOperator::Equals, $rstatus);
        }
        if (isset($sort) && isset($sortname)) {
            $refund->set_order_by($refund->get_field_by_name($sortname), $sort);
        } else {
            $refund->set_order_by(p_refundapply_soft::$field_id, 'desc');
        }
        if (!$is_manager) {
//            $refund->set_where_and(p_refundapply_soft::$field_customer_id, SqlOperator::Equals, $login_userid);
        }
        $refund->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $refund, NULL, true);
        if (!$result[0]) {
            die_error(USER_ERROR, '获取退款申请信息失败，请重试');
        }
        $employees = get_employees();
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($employees) {
                    $d['done_username'] = $employees[$d['done_userid']]['username'];
                });
        $user_array = array();
        array_walk($employees, function($d) use(&$user_array) {
            if (in_array($d['dept1_id'], array(11, 17)) && !str_equals(substr($d['username'], -1), ")")) {
                array_push($user_array, array('id' => $d['userid'], 'text' => $d['username']));
            }
        });
        echo_list_result($result, $models, array('user_list' => $user_array));
    }
    if ($action == 11) {
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();

        $refund = new p_refundapply_soft();
        if (isset($startTime)) {
            $refund->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') >= '" . $startTime . "'");
        }
        if (isset($endTime)) {
            $refund->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') <= '" . $endTime . "' ");
        }
        $field = array('addtime', 'rstatus', 'name', 'ww', 'qq', 'mobile', 'money', 'arrears', 'payment', 'nick_name', 'int_time', 'end_time', 'done_username');
        $refund->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $refund);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('退款申请数据导出失败,请稍后重试!')), "退款申请数据导出", "退款申请");
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    switch ((int) $d['rstatus']) {
                        case 1:
                            $d['rstatus'] = "退款";
                            break;
                        case 2:
                            $d['rstatus'] = "售后";
                            break;
                        case 3:
                            $d['rstatus'] = "其他";
                            break;
                    }
                    $d['addtime'] = explode(' ', $d['addtime'])[0];
                    $d['int_time'] = explode(' ', $d['int_time'])[0];
                    $d['end_time'] = explode(' ', $d['end_time'])[0];
                });
        $title_array = array('申请日期', '申请方式', '客户姓名', '旺旺', 'QQ', '客户电话', '销售金额', '欠款金额', '所属店铺', '所属客服', '介入时间', '完成时间', '操作人员');
        $export->set_field($field);
        $export->create($title_array, $models, "退款申请数据导出", "退款申请数据");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $refundData = request_object();
    //添加退款申请
    if ($action == 1) {
        $refund = new p_refundapply_soft();
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
        $refund = new p_refundapply_soft();
        $refund->set_field_from_array($refundData);
        $db = create_pdo();
        $result = $refund->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存退款资料失败');
        echo_msg('保存成功');
    }
    //删除退款
    if ($action == 3) {
        $refund = new p_refundapply_soft();
        $refund->set_field_from_array($refundData);
        $db = create_pdo();
        $result = $refund->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除失败');
        echo_msg('删除成功');
    }
    /**
     * 介入
     */
    if ($action == 4) {
        $rid = request_string('rid');
        $done_userid = request_int('done');
        if (!isset($rid)) die_error(USER_ERROR, '退款介入失败,请稍后重试~');
        $rid = explode(",", $rid);
        $refund_array = array();
        foreach ($rid as $v) {
            $refund = new p_refundapply_soft();
            $refund->set_int_time('now');
            $refund->set_done_userid($done_userid);
            $employee = get_employees()[$done_userid];
            $refund->set_done_username($employee['username']);
            $refund->set_where_and(p_refundapply_soft::$field_id, SqlOperator::Equals, (int) $v);
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
        $refund = new p_refundapply_soft();
        $refund->set_end_time('now');
        $refund->set_where_and(p_refundapply_soft::$field_id, SqlOperator::Equals, $rid);
        $db = create_pdo();
        $result = $refund->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '完成退款申请失败,请稍后重试~');
        echo_code(0);
    }
});

