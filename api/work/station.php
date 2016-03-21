<?php

/**
 * 员工列表/员工增删改操作
 *
 * @author Qi
 * @copyright 2015 星密码
 * @version 2015/3/3
 */
use Models\Base\Model;
use Models\W_Station;
use Models\M_Role;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $db = create_pdo();
    if (!isset($action)) $action = -1;
    $user = new W_Station();
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $deptid = request_int('deptid');
    if (isset($deptid)) {
        $user->begin_where_group_condition();
        $user->set_where_and(W_Station::$field_dept1_id, SqlOperator::Equals, $deptid);
        $user->set_where_or(W_Station::$field_dept2_id, SqlOperator::Equals, $deptid);
        $user->end_where_group_condition();
    }
    if (isset($sort) && isset($sortname)) {
        $user->set_order_by($user->get_field_by_name($sortname), $sort);
    } else {
            $user->set_order_by(W_Station::$field_role_id);
    }
    $user->set_limit_paged(request_pageno(), request_pagesize());
    $result = Model::query_list($db, $user, NULL, true);
    
    if (!$result[0]) die_error(USER_ERROR, '获取资料失败，请重试');
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $userData = request_object();
    //添加员工信息
    if ($action == 1) {
        $user = new W_Station();
        $user->set_field_from_array($userData);
        $em = get_roles()[$userData->role_id];
        $user->set_dept1_id($em['dept1_id']);
        //$user->set_role_id($userData->role_id);
        $user->set_addtime('now');
        $db = create_pdo();
        pdo_transaction($db, function($db) use($user) {
            $result = $user->insert($db);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存岗位职责失败。' . $result['detail_cn'], $result);
        });
        echo_msg('添加成功');
    }
    //修改员工信息
    if ($action == 2) {
        $user = new W_Station($userData->role_id);
        $user->set_field_from_array($userData);
        $em = get_roles()[$userData->role_id];
        $user->set_dept1_id($em['dept1_id']);
        $user->set_role_id($userData->role_id);
        $db = create_pdo();
        $result = $user->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存岗位职责失败');
        echo_msg('保存岗位职责成功');
    }
    if ($action == 3) {
        $user = new W_Station();
        $user->set_field_from_array($userData);
        $db = create_pdo();
        $result = $user->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除失败');
        echo_msg('删除成功');
    }
});
