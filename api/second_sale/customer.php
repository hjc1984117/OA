<?php

/**
 * 销售业绩售后表
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/3/16
 */
use Models\Base\Model;
use Models\P_Customerrecord_second;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if (!isset($action)) $action = -1;
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    $type = request_int('type');
    if ($action == 1) {
        $customer = new P_Customerrecord_second();
        if (isset($searchName)) {
            $customer->set_where_and(P_Customerrecord_second::$field_username, SqlOperator::Like, "%" . $searchName . "%");
        }
        if (isset($type)) {
            $customer->set_where_and(P_Customerrecord_second::$field_type, SqlOperator::Equals, $type);
        }
        if (isset($sort) && isset($sortname)) {
            $customer->set_order_by($customer->get_field_by_name($sortname), $sort);
        } else {
            $customer->set_order_by(P_Customerrecord_second::$field_id, 'ASC');
        }
        $customer->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $customer, NULL, true);
        if (!$result[0]) {
            die_error(USER_ERROR, '获取售后资料失败，请重试');
        }
        $users = get_employees();
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($users) {
                    $d['usernickname'] = $d['username'] . '(' . $d['nickname'] . ')';
                });
        echo_list_result($result, $models);
    }
    if ($action == 2) {
        $customer = new P_Customerrecord_second();
        $customer->set_where_and(P_Customerrecord_second::$field_status, SqlOperator::Equals, 1);
        if (isset($type)) {
            $customer->set_where_and(P_Customerrecord_second::$field_type, SqlOperator::Equals, $type);
        }
        $customer->set_query_fields(array('userid', 'username', 'nickname'));
        $db = create_pdo();
        $customer_result = Model::query_list($db, $customer);
        $customer_list = Model::list_to_array($customer_result['models'], array(), function(&$d) {
                    $d['id'] = $d['userid'];
                    $d['text'] = $d['username'] . "(" . $d['nickname'] . ")";
                    unset($d['userid']);
                    unset($d['username']);
                    unset($d['nickname']);
                });
        echo_result($customer_list);
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $customerData = request_object();
    $customerData->qqReception = !isset($customerData->qqReception) ? 0 : $customerData->qqReception;
    $customerData->tmallReception = !isset($customerData->tmallReception) ? 0 : $customerData->qqReception;
    if ($action == 1) {
        $customer = new P_Customerrecord_second();
        $customer->set_field_from_array($customerData);
        $customerup = (array) $customerData;
        //$customer->set_username($customerup["username"] . "老师");
        $db = create_pdo();
        pdo_transaction($db, function($db) use($customer) {
            $result = $customer->insert($db);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存售后资料失败。' . $result['detail_cn'], $result);
        });
        echo_msg('添加成功');
    }
    //修改销售售后信息
    if ($action == 2) {
        $customer = new P_Customerrecord_second();
        $customer->set_field_from_array($customerData);
        $db = create_pdo();
        $result = $customer->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存售后资料失败');
        echo_msg('保存成功');
    }
    if ($action == 5) {
        $customer = new P_Customerrecord_second($customerDate->id);
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
        $customer = new P_Customerrecord_second();
        $customer->set_field_from_array($customerData);
        $db = create_pdo();
        $result = $customer->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除失败');
        echo_msg('删除成功');
    }
    //启用
    if ($action == 4) {
        $db = create_pdo();
        $sql = "update P_Customerrecord_second SET status = 1";
        pdo_transaction($db, function($db) use($sql) {
            $result = Model::execute_custom_sql($db, $sql);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '启动失败~' . $result['detail_cn'], $result);
        });
        echo_msg('启用成功');
    }

    //全部暂停
    if ($action == 41) {
        $db = create_pdo();
        $sql = "update P_Customerrecord_second SET status = 0";
        pdo_transaction($db, function($db) use($sql) {
            $result = Model::execute_custom_sql($db, $sql);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '暂停失败~' . $result['detail_cn'], $result);
        });
        echo_msg('启用成功');
    }
});

