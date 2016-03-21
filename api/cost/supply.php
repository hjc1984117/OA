<?php

/**
 * 领料
 *
 * @author Qi
 * @copyright 2015 星密码
 * @version 2015/3/7
 */
use Models\Base\Model;
use Models\C_Supply;
use Models\C_Repertory;
use Models\Base\SqlOperator;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $supply = new C_Supply();
        $sort = request_string('sort');
        $deptid = request_int('deptid');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');
        $searchDate = request_string('searchDate');
        if (isset($deptid)) {
            $supply->set_where_and(C_Supply::$field_dept1_id, SqlOperator::Equals, $deptid);
        }
        if (isset($searchName)) {
            $supply->set_where_and(C_Supply::$field_goods, SqlOperator::Like, '%' . $searchName . '%');
        }
        if (isset($sort) && isset($sortname)) {
            $supply->set_order_by($supply->get_field_by_name($sortname), $sort);
        } else {
            $supply->set_order_by(C_Supply::$field_addtime, 'DESC');
        }
        if (isset($searchDate)) {
            $supply->set_where_and(C_Supply::$field_addtime, SqlOperator::Between, array($searchDate . ' 00.00.00', $searchDate . ' 23:59:59'));
        }
        $supply->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $supply, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取领料资料失败，请重试');
        $models = Model::list_to_array($result['models'], array(), "id_2_text");
        echo_list_result($result, $models);
    }
    if ($action == 11) {
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $supply = new C_Supply();
        if (isset($startTime)) {
            $supply->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $supply->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') <= '" . $endTime . "' ");
        }
        $field = array('addtime', 'goods', 'num', 'price', 'unit_price', 'dept1_id', 'username', 'way', 'remarks');
        $supply->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $supply, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('领料数据导出失败,请稍后重试!')), "领料数据导出", "领料");
        }
        $depts = get_depts();
        $models = Model::list_to_array($result['models'], array(), function(&$d) use ($depts) {
                    $d['dept1_id'] = $depts[$d['dept1_id']]['text'];
                });
        $title_array = array('领料日期', '领用物品', '数量', '金额', '单价', '领用部门', '领用人', '领料用途', '备注');
        $export->set_field($field);
        $export->create($title_array, $models, "领料数据导出", "领料");
    }
});
execute_request(HttpRequestMethod::Post, function() use($action) {
    $supplyData = request_object();
    //添加领料信息
    if ($action == 1) {
        $supply = new C_Supply();
        $supply->set_field_from_array($supplyData);
        $employee = get_employees()[$supplyData->userid];
        $supply->set_dept1_id($employee['dept1_id']);
        $supply->set_dept2_id($employee['dept2_id']);
        if (!isset($supplyData->addtime)) {
            $supply->set_adddate('now');
        }
        $db = create_pdo();
        $repertor = new C_Repertory();
        $repertor->set_where_and(C_Repertory::$field_goods_name, SqlOperator::Equals, $supply->get_goods());
        $result = $repertor->load($db, $repertor);
        if (!$result[0]) {
            echo_result(array('code' => 10, 'msg' => "库存中暂无该物品,请核对后重试~~"));
        } else {
            if (($repertor->get_surplus_count()) < ($supply->get_num())) {
                echo_result(array('code' => 10, 'msg' => "库存不足,目前最大领取数为<label style='color:red;font-size:25px;'>" . ($repertor->get_surplus_count()) . "</label>,请核对后重试~"));
            }
            $repertor->set_surplus_count(($repertor->get_surplus_count()) - ($supply->get_num()));
            $supply->set_goods_id($repertor->get_id());
            pdo_transaction($db, function($db) use($supply, $repertor) {
                $result = $supply->insert($db);
                if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '领料失败。' . $result['detail_cn'], $result);
                $result_repertor = $repertor->update($db);
                if (!$result_repertor[0]) throw new TransactionException(PDO_ERROR_CODE, '领料失败。' . $result_repertor['detail_cn'], $result_repertor);
            });
        }
        echo_msg('领料成功');
    }
    //修改领料信息
    if ($action == 2) {
        $supply = new C_Supply();
        $supply->set_field_from_array($supplyData);
        $employee = get_employees()[$supplyData->userid];
        $supply->set_dept1_id($employee['dept1_id']);
        $supply->set_dept2_id($employee['dept2_id']);
        $db = create_pdo();
        $result = $supply->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存领料信息失败');
        echo_msg('保存成功');
    }

    if ($action = 3) {
        $supply = new C_Supply();
        $supply->set_field_from_array($supplyData);
        $db = create_pdo();
        $result = $supply->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除领料信息失败');
        echo_msg('删除成功');
    }
});
