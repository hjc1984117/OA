<?php

/**
 * 领款单
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/3/11
 */
use Models\Base\Model;
use Models\B_Drawmoney;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $db = create_pdo();
    if (!isset($action)) $action = -1;
    $draw = new B_Drawmoney();
    $status = request_int('status');
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $deptid = request_int('deptid');
    $searchName = request_string('searchName');
//    filter_numeric($status, 0);
    if ($status > 0 && !isset($searchName)) {
        $draw->set_where(B_Drawmoney::$field_status, SqlOperator::Equals, $status);
    } else {
        $draw->set_where(B_Drawmoney::$field_username, SqlOperator::Like, '%' . $searchName . '%');
    }
    if (isset($deptid)) {
        $draw->set_where_and(B_Drawmoney::$field_dept1_id, SqlOperator::Equals, $deptid);
        $draw->set_where_or(B_Drawmoney::$field_dept2_id, SqlOperator::Equals, $deptid);
    }
    if (isset($sort) && isset($sortname)) {
        $draw->set_order_by($draw->get_field_by_name($sortname), $sort);
    } else {
        $draw->set_order_by(B_Drawmoney::$field_id, 'ASC');
    }
    get_record_by_role($draw);
    $draw->set_limit_paged(request_pageno(), request_pagesize());
    $result = Model::query_list($db, $draw, NULL, true);
    if (!$result[0]) {
        die_error(USER_ERROR, '获取领款资料失败，请重试');
    }
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    $roletype = get_role_type();
    array_walk($models, function(&$model)use($draw, $roletype) {
        $workflow_config = get_drawmoney_workflow($model);
        get_workflow($workflow_config, $model, $roletype);
    });
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $drawData = request_object();
    $draw = new B_Drawmoney($drawData->id);
    //添加信息
    if ($action == 1) {
        $draw->set_field_from_array($drawData);
        $employee = get_employees()[$drawData->userid];
        $draw->set_dept1_id($employee['dept1_id']);
        $draw->set_dept2_id($employee['dept2_id']);
        $draw->set_addtime("now");
        $draw->set_status(0);
        $db = create_pdo();
        pdo_transaction($db, function($db) use($draw) {
            $result = $draw->insert($db);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存资料失败。' . $result['detail_cn'], $result);
        });
        echo_msg('添加成功');
    }
    //修改信息
    if ($action == 2) {
        $workflow_configs = get_drawmoney_workflow((array) $drawData);
        $db = create_pdo();
        set_workflow_status($db, $drawData, $workflow_configs, $draw);
        $result = $draw->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '修改领款信息失败~');
        echo_msg('修改领款信息成功~');
    }
    //删除
    if ($action == 3) {
        $drawmoney = request_object();
        $draw = new B_Drawmoney();
        $draw->set_field_from_array($drawmoney);
        $db = create_pdo();
        $result = $draw->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除领款信息失败~');
        echo_msg('删除领款信息成功~');
    }
    //修改信息
    if ($action == 4) {
        $drawmoney = request_object();
        $draw = new B_Drawmoney();
        $draw->set_field_from_array($drawData);
        $employee = get_employees()[$drawData->userid];
        $draw->set_dept1_id($employee['dept1_id']);
        $draw->set_dept2_id($employee['dept2_id']);
        $db = create_pdo();
        $result = $draw->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '修改领款信息失败~');
        echo_msg('修改领款信息成功~');
    }
});
