<?php

/**
 * 员工列表/员工增删改操作
 *
 * @author Qi
 * @copyright 2015 星密码
 * @version 2015/3/3
 */
use Models\Base\Model;
use Models\M_Role;
use Models\W_WeekWork;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $db = create_pdo();
    if (!isset($action)) $action = -1;
    $work = new W_WeekWork();
    $work->set_order_by(W_WeekWork::$field_insert_time);
    $work->set_limit_paged(request_pageno(), request_pagesize());
    $result = Model::query_list($db,$work, NULL, true);
    if (!$result[0]) die_error(USER_ERROR, '获取申购资料失败，请重试');
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $workData = request_object();
    //添加工作总结
    if ($action == 1) {
        $work = new W_WeekWork();
        $work->set_field_from_array($workData);
        $employee = get_employees()[$workData->user_id];
        $work->set_dept1_id($employee['dept1_id']);
        $work->set_dept2_id($employee['dept2_id']);
        $work->set_role_id($employee['role_id']);
        $work->set_insert_time('now');
        $db = create_pdo();
        $result = $work->insert($db);
        if (!$result[0]) die_error(USER_ERROR,'添加工作总结失败');
        echo_msg('添加成功');
    }
    //修改工作总结
    if ($action == 2) {
        $work = new W_WeekWork();
        $work->set_field_from_array($workData);
        $work->set_insert_time('now');
        $db = create_pdo();
        $result = $work->update($db, true);
        if (!$result[0])
           die_error(USER_ERROR, '保存工作总结失败');
        echo_msg('保存成功');
    }
});
