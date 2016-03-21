<?php

/**
 * 库存系统
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/8/19
 */
use Models\Base\Model;
use Models\Base\SqlOperator;
use Models\C_Repertory;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    $repertory = new C_Repertory();
    if (isset($searchName)) {
        $repertory->set_where_and(C_Repertory::$field_goods_name, SqlOperator::Like, "%" . $searchName . "%");
    }
    if (isset($sort) && isset($sortname)) {
        $repertory->set_order_by($repertory->get_field_by_name($sortname), $sort);
    } else {
        $repertory->set_order_by(C_Repertory::$field_id, 'DESC');
    }
    $repertory->set_limit_paged(request_pageno(), request_pagesize());
    $db = create_pdo();
    $result = Model::query_list($db, $repertory, NULL, true);
    if (!$result[0]) die_error(USER_ERROR, '获取库存资料失败，请重试');
    $models = Model::list_to_array($result['models']);
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $repertoryData = request_object();
    //添加库存信息
//    if ($action == 1) {
//        $applyCost = new C_Repertory();
//        $applyCost->set_field_from_array($repertoryData);
//        $employee = get_employees()[$repertoryData->userid];
//        $applyCost->set_dept1_id($employee['dept1_id']);
//        $applyCost->set_dept2_id($employee['dept2_id']);
//        $applyCost->set_date('now');
//        $db = create_pdo();
//        pdo_transaction($db, function($db) use($applyCost) {
//            $result = $applyCost->insert($db);
//            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '添加库存资料失败。' . $result['detail_cn'], $result);
//        });
//        echo_msg('添加成功');
//    }
    //修改库存信息(流程)
    if ($action == 2) {
        $applyCost = new C_Repertory();
        $applyCost->set_field_from_array($repertoryData);
        $db = create_pdo();
        $result = $applyCost->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存库存资料失败');
        echo_msg('保存成功');
    }

    //删除库存信息
    if ($action == 3) {
        $applyCost = new C_Repertory($repertoryData->id);
        $db = create_pdo();
        $result = $applyCost->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除库存信息失败~');
        echo_msg('删除库存信息成功~');
    }
});
