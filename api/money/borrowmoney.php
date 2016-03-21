<?php

/**
 * 借款单
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/3/11
 */
use Models\Base\Model;
use Models\B_Borrowmoney;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $db = create_pdo();
    if (!isset($action)) $action = -1;
    $borrow = new B_Borrowmoney();
    $status = request_int('status');
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $deptid = request_int('deptid');
    $searchName = request_string('searchName');
//    filter_numeric($status, 0);
    if ($status > 0 && !isset($searchName)) {
        $borrow->set_where(B_Borrowmoney::$field_status, SqlOperator::Equals, $status);
    } else {
        $borrow->set_where(B_Borrowmoney::$field_username, SqlOperator::Like, '%' . $searchName . '%');
    }
    if (isset($deptid)) {
        $borrow->set_where_and(B_Borrowmoney::$field_dept1_id, SqlOperator::Equals, $deptid);
        $borrow->set_where_or(B_Borrowmoney::$field_dept2_id, SqlOperator::Equals, $deptid);
    }
    if (isset($sort) && isset($sortname)) {
        $borrow->set_order_by($borrow->get_field_by_name($sortname), $sort);
    } else {
        $borrow->set_order_by(B_Borrowmoney::$field_id, 'ASC');
    }
    get_record_by_role($borrow);
    $borrow->set_limit_paged(request_pageno(), request_pagesize());
    $result = Model::query_list($db, $borrow, NULL, true);
    if (!$result[0]) {
        die_error(USER_ERROR, '获取借款资料失败，请重试');
    }
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    $roletype = get_role_type();
    array_walk($models, function(&$model)use($borrow, $roletype) {
        $workflow_config = get_borrowmoney_workflow($model);
        get_workflow($workflow_config, $model, $roletype);
    });
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $borrowData = request_object();
    //添加员工信息
    if ($action == 1) {
        $borrow = new B_Borrowmoney();
        $borrow->set_field_from_array($borrowData);
        $employee = get_employees()[$borrowData->userid];
        $borrow->set_dept1_id($employee['dept1_id']);
        $borrow->set_dept2_id($employee['dept2_id']);
        $borrow->set_addtime("now");
        $borrow->set_status(0);
        $db = create_pdo();
        pdo_transaction($db, function($db) use($borrow) {
            $result = $borrow->insert($db);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存借款资料失败。' . $result['detail_cn'], $result);
        });
        echo_msg('添加成功');
    }
    //更新
    if ($action == 2) {
        $borrow = new B_Borrowmoney($borrowData->id);
        $workflow_configs = get_borrowmoney_workflow((array) $borrowData);
        $db = create_pdo();
        set_workflow_status($db, $borrowData, $workflow_configs, $borrow);
        $result = $borrow->update($db);
        if (!$result[0]) die_error(USER_ERROR, '修改借款信息失败~');
        echo_msg('修改借款信息成功~');
    }
    //删除
    if ($action == 3) {
        $borrowDate = request_object();
        $borrow = new B_Borrowmoney();
        $borrow->set_field_from_array($borrowDate);
        $db = create_pdo();
        $result = $borrow->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除借款信息失败~');
        echo_msg('删除借款信息成功~');
    }
    //修改员工信息
    if ($action == 4) {
        $borrowDate = request_object();
        $borrow = new B_Borrowmoney();
        $borrow->set_field_from_array($borrowData);
        $employee = get_employees()[$borrowData->userid];
        $borrow->set_dept1_id($employee['dept1_id']);
        $borrow->set_dept2_id($employee['dept2_id']);
        $db = create_pdo();
        $result = $borrow->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '修改借款信息失败~');
        echo_msg('修改借款信息成功~');
    }
});
