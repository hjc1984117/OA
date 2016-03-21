<?php

/**
 * 销售业绩售后表
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/3/16
 */
use Models\Base\Model;
use Models\P_Customerrecord_soft;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if (!isset($action)) $action = -1;
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    $sql = "SELECT pc.id,pc.userid,pc.username,pc.nickname,pc.toplimit,IFNULL(ps.finish,0) AS finish,pc.`status`,pc.qqReception,pc.starttime,pc.endtime FROM P_Customerrecord_soft pc ";
    $sql .= "LEFT JOIN (SELECT ps.id,ps.customer,ps.customer_id, COUNT(ps.customer_id) AS finish FROM P_Salecount_soft ps WHERE " . getWhereSql("ps") . " AND ps.customer_id != 0 GROUP BY ps.customer_id ) AS ps ON pc.userid = ps.customer_id ";
    if (isset($searchName)) {
        $sql .= " AND pc.username like '%" . $searchName . "%'";
    }
    if (isset($sort) && isset($sortname)) {
        if ($sortname == 'finish') {
            $sql .= " ORDER BY ps." . $sortname . " " . $sort;
        } else {
            $sql .= " ORDER BY pc." . $sortname . " " . $sort;
        }
    } else {
        $sql .= " ORDER BY pc.id ASC";
    }

    $db = create_pdo();
    $customer = new P_Customerrecord_soft();
    $result_total_count = $customer->count($db);
    $customer->reset();
    $sql .= " LIMIT " . request_pagesize() * (request_pageno() - 1) . "," . request_pagesize();
    $result = Model::query_list($db, $customer, $sql);
    if (!$result[0]) {
        die_error(USER_ERROR, '获取售后资料失败，请重试');
    }
    $users = get_employees();
    $models = Model::list_to_array($result['models'], array(), function(&$d) use($users) {
                $d['usernickname'] = $d['username'] . '(' . $d['nickname'] . ')';
            });
    echo_result(array('total_count' => $result_total_count, 'list' => $models, 'page_no' => request_pageno(), 'max_page_no' => ceil($result_total_count / request_pagesize())));
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $customerData = request_object();
    $customerData->qqReception = !isset($customerData->qqReception) ? 0 : $customerData->qqReception;
    if ($action == 1) {
        //判断重复添加
        $customer = new P_Customerrecord_soft();
        $customer->set_where_and(p_customerrecord_soft::$field_userid, SqlOperator::Equals, $customerData->userid);
        $db = create_pdo();
        $customer_results = Model::query_list($db, $customer);
        if($customer_results['count'] > 0) die_error(USER_ERROR, '请不要重复添加。');
       
        $customer = new P_Customerrecord_soft();
        $customer->set_group('1');
        $customer->set_team('1');
        $customer->set_category("C");
        $customer->set_field_from_array($customerData);
        $customerup = (array) $customerData;
        $db = create_pdo();
        pdo_transaction($db, function($db) use($customer) {
            $result = $customer->insert($db);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存售后资料失败。' . $result['detail_cn'], $result);
        });
        echo_msg('添加成功');
    }
    //修改销售售后信息
    if ($action == 2) {
        $customer = new P_Customerrecord_soft();
        $customer->set_field_from_array($customerData);
        $db = create_pdo();
        $result = $customer->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存售后资料失败');
        echo_msg('保存成功');
    }
    if ($action == 5) {
        $customer = new P_Customerrecord_soft($customerDate->id);
        $customer->set_field_from_array($customerData);
        $toplimit = $customerData->toplimit;
        $finish = $customerData->finish;
        if ($toplimit <= $finish) {
            die_error(USER_ERROR, '用户到达接单上限');
            exit;
        }
        $db = create_pdo();
        $result = $customer->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存售后资料失败');
        echo_msg('保存成功');
    }
    //删除
    if ($action == 3) {
        $customer = new P_Customerrecord_soft();
        $customer->set_field_from_array($customerData);
        $db = create_pdo();
        $result = $customer->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除失败');
        echo_msg('删除成功');
    }
    //启用
    if ($action == 4) {
        $db = create_pdo();
        $sql = "update P_Customerrecord_soft SET status = 1";
        pdo_transaction($db, function($db) use($sql) {
            $result = Model::execute_custom_sql($db, $sql);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '启动失败~' . $result['detail_cn'], $result);
        });
        echo_msg('启用成功');
    }

    //全部暂停
    if ($action == 41) {
        $db = create_pdo();
        $sql = "update P_Customerrecord_soft SET status = 0";
        pdo_transaction($db, function($db) use($sql) {
            $result = Model::execute_custom_sql($db, $sql);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '暂停失败~' . $result['detail_cn'], $result);
        });
        echo_msg('启用成功');
    }
});

