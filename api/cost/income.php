<?php

/**
 * 行政收入
 *
 * @author Qi
 * @copyright 2015 星密码
 * @version 2015/3/4
 */
use Models\Base\Model;
use Models\C_Income;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $db = create_pdo();
    if (!isset($action)) $action = -1;
    $income = new C_Income();
    $status = request_int('status');
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    filter_numeric($status, 1);
    if ($status > 0) {
        $income->set_where_and(C_Income::$field_status, SqlOperator::Equals, $status);
    }
    if (isset($sort) && isset($sortname)) {
        $income->set_order_by($income->get_field_by_name($sortname), $sort);
    } else {
        $income->set_order_by(C_Income::$field_id, 'ASC');
    }
    //$status = request_int('status');
    //filter_numeric($status, 1);
    //if ($status > 0) $income->set_where(C_Income::$field_status, SqlOperator::Equals, $status);
    $income->set_limit_paged(request_pageno(), request_pagesize());
    $result = Model::query_list($db, $income, NULL, true);
    if (!$result[0]) die_error(USER_ERROR, '获取行政收入资料失败，请重试');
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    echo_list_result($result, $models);
});
execute_request(HttpRequestMethod::Post, function() use($action) {
    $incomeData = request_object();
    //添加行政收入信息
    if ($action == 1) {
        $income = new C_Income();
        $income->set_field_from_array($incomeData);
        $employee = get_employees()[$incomeData->userid];
        $income->set_dept1_id($employee['dept1_id']);
        $income->set_dept2_id($employee['dept2_id']);
        $income->set_addtime("now");
        $db = create_pdo();
        pdo_transaction($db, function($db) use($income) {
            $result = $income->insert($db);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存行政收入失败。' . $result['detail_cn'], $result);
        });
        echo_msg('添加成功');
    }
    //修改行政收入信息
    if ($action == 2) {
        $income = new C_Income();
        $income->set_field_from_array($incomeData);
        $db = create_pdo();
        $result = $income->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存行政收入信息失败');
        echo_msg('保存成功');
    }
    
    //删除行政收入信息
    if ($action = 3) {
        $income = new C_Income();
        $income->set_field_from_array($incomeData);
        $db = create_pdo();
        $result = $income->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除行政收入信息失败');
        echo_msg('删除成功');
    }
});
