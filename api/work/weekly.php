<?php

/**
 * 周报列表
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/3/21
 */
use Models\Base\Model;
use Models\W_Weekly;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $db = create_pdo();
    if (!isset($action))$action = -1;
    $weekly = new W_Weekly();
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    filter_numeric($status, 1);
    if (isset($searchName)) {
        $weekly->set_where(W_Weekly::$field_username, SqlOperator::Like, '%' . $searchName . '%');
    }
    if (isset($sort) && isset($sortname)) {
        $weekly->set_order_by($weekly->get_field_by_name($sortname), $sort);
    } else {
        $weekly->set_order_by(W_Weekly::$field_id, 'ASC');
    }
    $weekly->set_limit_paged(request_pageno(), request_pagesize());
    $result = Model::query_list($db, $weekly, NULL, true);
    if (!$result[0]) {
        die_error(USER_ERROR, '获取周报失败，请重试');
    }
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $weeklyData = request_object();
    if ($action == 1) {
        $weekly = new W_Weekly();
        $weekly->set_field_from_array($weeklyData);
        $employee = get_employees()[request_userid()];
        $weekly->set_userid(request_userid());
        $weekly->set_dept1_id($employee['dept1_id']);
        $weekly->set_dept2_id($employee['dept2_id']);
        $weekly->set_username(request_username());
        $weekly->set_addtime("now");
        $db = create_pdo();
        pdo_transaction($db, function($db) use($weekly) {
            $result = $weekly->insert($db);
            if (!$result[0])throw new TransactionException(PDO_ERROR_CODE, '保存周报失败。' . $result['detail_cn'], $result);            
        });
        echo_msg('添加成功');
    }
    //修改销售统计信息
    if ($action == 2) {
        $weekly = new W_Weekly();
        $weekly->set_field_from_array($weeklyData);
        $db = create_pdo();
        $result = $weekly->update($db, true);
        if (!$result[0])
            die_error(USER_ERROR, '保存周报失败');
        echo_msg('保存成功');
    }
    //删除
    if ($action == 3) {
        $weekly = new W_Weekly();
        $weekly->set_field_from_array($weeklyData);
        $db = create_pdo();
        $result = $weekly->delete($db, true);
        if (!$result[0])die_error(USER_ERROR, '删除失败');
        echo_msg('删除成功');
    }
});

