<?php

/**
 * 费用报销列表
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/3/11
 */
use Models\Base\Model;
use Models\E_Expense;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $db = create_pdo();
    if (!isset($action)) $action = -1;
    $expense = new E_Expense();
    $status = request_int('status');
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $deptid = request_int('deptid');
    $searchName = request_string('searchName');
    filter_numeric($status, 0);
    if ($status > 0 && !isset($searchName)) {
        $expense->set_where(E_Expense::$field_status, SqlOperator::Equals, $status);
    } else {
        $expense->set_where(E_Expense::$field_username, SqlOperator::Like, '%' . $searchName . '%');
    }
    if (isset($deptid)) {
        $expense->set_where_and(E_Expense::$field_dept1_id, SqlOperator::Equals, $deptid);
        $expense->set_where_or(E_Expense::$field_dept2_id, SqlOperator::Equals, $deptid);
    }
    if (isset($sort) && isset($sortname)) {
        $expense->set_order_by($expense->get_field_by_name($sortname), $sort);
    } else {
        $expense->set_order_by(E_Expense::$field_id, 'ASC');
    }
    get_record_by_role($expense);
    $expense->set_limit_paged(request_pageno(), request_pagesize());
    $result = Model::query_list($db, $expense, NULL, true);
    if (!$result[0]) {
        die_error(USER_ERROR, '获取报销资料失败，请重试');
    }
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    $roletype = get_role_type();
    array_walk($models, function(&$model)use($roletype) {
        $workflow_config = get_expense_workflow($model);
        get_workflow($workflow_config, $model, $roletype);
    });
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $expenseData = request_object();
    $expense = new E_Expense($expenseData->id);
    if ($action == 1) {
        $expense->set_field_from_array($expenseData);
        $employee = get_employees()[$expenseData->userid];
        $expense->set_dept1_id($employee['dept1_id']);
        $expense->set_dept2_id($employee['dept2_id']);
        //$expense->set_status(1);
        $expense->set_addtime("now");
        $db = create_pdo();
        pdo_transaction($db, function($db) use($expense) {
            $result = $expense->insert($db);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存报销资料失败。' . $result['detail_cn'], $result);
        });
        echo_msg('添加成功');
    }
    //修改信息
    if ($action == 2) {
        $workflow_configs = get_expense_workflow((array) $expenseData);
        $db = create_pdo();
        set_workflow_status($db, $expenseData, $workflow_configs, $expense);
        $result = $expense->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '修改报销信息失败~');
        echo_msg('修改报销信息成功~');
    }
    //删除
    if ($action == 3) {
        $expense = new E_Expense();
        $expense->set_field_from_array($expenseData);
        $db = create_pdo();
        $result = $expense->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除失败');
        echo_msg('删除成功');
    }
    //修改
    if ($action == 4) {
        $expenseData = request_object();
        $expense = new B_Borrowmoney();
        $expense->set_field_from_array($expenseData);
        $employee = get_employees()[$expenseData->userid];
        $expense->set_dept1_id($employee['dept1_id']);
        $expense->set_dept2_id($employee['dept2_id']);
        $db = create_pdo();
        $result = $expense->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '修改报销信息失败~');
        echo_msg('修改报销信息成功~');
    }
});
