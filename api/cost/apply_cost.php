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
use Models\C_ApplyBuy;
use Models\C_Repertory;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    request_userid();
    $db = create_pdo();
    $deptid = request_int('deptid');
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    $searchDate = request_string('searchDate');
    $applCost = new C_ApplyBuy();
    if (isset($searchDate)) {
        $applCost->set_where_and(C_ApplyBuy::$field_date, SqlOperator::Between, array($searchDate . ' 0:00:00', $searchDate . ' 23:59:59'));
    }
    if (isset($deptid)) {
        $applCost->set_where_and(C_ApplyBuy::$field_dept1_id, SqlOperator::Equals, $deptid);
    }
    if (isset($searchName)) {
        $applCost->set_where_and(C_ApplyBuy::$field_goods, SqlOperator::Like, '%' . $searchName . '%');
    }
    if (isset($sort) && isset($sortname)) {
        $applCost->set_order_by($applCost->get_field_by_name($sortname), $sort);
    } else {
        $applCost->set_order_by(C_ApplyBuy::$field_add_date, 'DESC');
    }
//    get_record_by_role($applCost);
    $applCost->set_limit_paged(request_pageno(), request_pagesize());
    $result = Model::query_list($db, $applCost, NULL, true);
    if (!$result[0]) die_error(USER_ERROR, '获取申购资料失败，请重试');
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
//    $roletype = get_role_type();
//    array_walk($models, function(&$model) use($roletype) {
//        $workflow_configs = get_apply_buy($model);
//        get_workflow($workflow_configs, $model, $roletype);
//    });
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $applyCostData = request_object();
    //添加申购信息
    if ($action == 1) {
        $applyCost = new C_ApplyBuy();
        $applyCost->set_field_from_array($applyCostData);
        $employee = get_employees()[$applyCostData->userid];
        if (empty($applyCostData->dept1_text)) $applyCost->set_dept1_id($employee['dept1_id']);
        $applyCost->set_dept2_id($employee['dept2_id']);
        if (str_equals(($applyCostData->dept1_text), '综合部')) {
            $applyCost->set_dept1_id(0);
            $applyCost->set_dept2_id(0);
        }
        $applyCost->set_goods(strtoupper($applyCost->get_goods()));
        if (!isset($applyCostData->add_date)) {
            $applyCost->set_add_date('now');
        }
        $applyCost->set_date('now');
        $db = create_pdo();
        $repertor = new C_Repertory();
        $repertor->set_where_and(C_Repertory::$field_goods_name, SqlOperator::Equals, $applyCost->get_goods());
        $result = $repertor->load($db, $repertor);
        if (!$result[0]) {
            $repertor->reset();
            $repertor->set_goods_name($applyCost->get_goods());
            $repertor->set_total_count($applyCost->get_num());
            $repertor->set_surplus_count($applyCost->get_num());
            $repertor->set_unit_price($applyCost->get_unit_price());
            $repertor->set_remark($applyCost->get_way());
            pdo_transaction($db, function($db) use($applyCost, $repertor) {
                $result = $applyCost->insert($db);
                if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '添加申购资料失败。' . $result['detail_cn'], $result);
                $result_repertor = $repertor->insert($db);
                if (!$result_repertor[0]) throw new TransactionException(PDO_ERROR_CODE, '添加申购资料失败。' . $result_repertor['detail_cn'], $result_repertor);
            });
        } else {
            $repertor->set_total_count(($repertor->get_total_count()) + ($applyCost->get_num()));
            $repertor->set_surplus_count(($repertor->get_surplus_count()) + ($applyCost->get_num()));
            pdo_transaction($db, function($db) use($applyCost, $repertor) {
                $result = $applyCost->insert($db);
                if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '添加申购资料失败。' . $result['detail_cn'], $result);
                $result_repertor = $repertor->update($db);
                if (!$result_repertor[0]) throw new TransactionException(PDO_ERROR_CODE, '添加申购资料失败。' . $result_repertor['detail_cn'], $result_repertor);
            });
        }
        echo_msg('添加成功');
    }
    //修改申购信息(流程)
    if ($action == 2) {
        $applyCost = new C_ApplyBuy();
        $applyCost->set_field_from_array($applyCostData);
        //$workflow_configs = get_apply_buy((array) $applyCostData);
        $db = create_pdo();
        //set_workflow_status($db, $applyCostData, $workflow_configs, $applyCost);
        $result = $applyCost->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存申购资料失败');
        echo_msg('保存成功');
    }

    //删除申购信息
    if ($action == 3) {
        $applyCost = new C_ApplyBuy($applyCostData->id);
        $db = create_pdo();
        $result = $applyCost->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除申购信息失败~');
        echo_msg('删除申购信息成功~');
    }

    if ($action == 4) {
        $applyCost = new C_ApplyBuy();
        $applyCost->set_field_from_array($applyCostData);
        $db = create_pdo();
        $result = $applyCost->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存申购资料失败');
        echo_msg('保存成功');
    }
});
