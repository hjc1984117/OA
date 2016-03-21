<?php

/**
 * 申购系统
 *
 * @author YanXiong
 * @copyright 2015 星密码
 * @version 2015/1/27
 */
use Models\Base\Model;
use Models\Base\SqlOperator;
use Models\C_Expenditure;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $db = create_pdo();
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    $expenditure = new C_Expenditure();
    if (isset($searchName)) {
        $expenditure->set_where(C_Expenditure::$field_user_name, SqlOperator::Like, '%' . $searchName . '%');
    }
    if (isset($sort) && isset($sortname)) {
        $expenditure->set_order_by($expenditure->get_field_by_name($sortname), $sort);
    } else {
        $expenditure->set_order_by(C_Expenditure::$field_date, 'DESC');
    }
    $expenditure->set_limit_paged(request_pageno(), request_pagesize());
    $result = Model::query_list($db, $expenditure, NULL, true);
    if (!$result[0]) die_error(USER_ERROR, '获取申购资料失败，请重试');
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $expendituretData = request_object();
    //添加支出信息
    if ($action == 1) {
        $expenditure = new C_Expenditure();
        $expenditure->set_field_from_array($expendituretData);
        $expenditure->set_date(date('Y-m-d'));
        $db = create_pdo();
        $result = $expenditure->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加支出失败');
        echo_msg('添加成功');
    }
    //修改支出信息
    if ($action == 2) {
        $expenditure = new C_Expenditure();
        $expenditure->set_field_from_array($expendituretData);
        $db = create_pdo();
        $result = $expenditure->update($db,true);
        if (!$result[0]) die_error(USER_ERROR, '保存支出失败');
        echo_msg('保存成功');
    }

    //删除支出信息
    if ($action == 3) {
        $expenditure = new C_Expenditure();
        $expenditure->set_field_from_array($expendituretData);
        $db = create_pdo();
        $result = $expenditure->delete($db);
        if (!$result[0]) die_error(USER_ERROR, '删除支出失败');
        echo_msg('删除成功');
    }
});
