<?php

/**
 * 代运营
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/7/13
 */
use Models\Base\Model;
use Models\P_GenerationOperation_soft;
use Models\Base\SqlOperator;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../common/http.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $login_userid = request_login_userid();
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $workName = request_string("workName");
    $ptqqName = request_string("ptqqName");
    $searchTime = request_string("searchTime");
    $searchStartTime = request_string("searchStartTime");
    $searchEndTime = request_string("searchEndTime");
    $qq = request_string("qq");
    if ($action == 1) {
        $generationOperation = new P_GenerationOperation_soft();
        if (isset($workName)) {
            $generationOperation->set_custom_where(" AND (platform_sales LIKE '%" . $workName . "%' OR customer LIKE '%" . $workName . "%' OR headmaster LIKE '%" . $workName . "%') ");
        }
        if (isset($ptqqName)) {
            $generationOperation->set_custom_where(" AND (platform_num LIKE '%" . $ptqqName . "%' OR qq LIKE '%" . $ptqqName . "%') ");
        }
        if (isset($searchTime)) {
            $generationOperation->set_custom_where(" AND DATE_FORMAT(add_time,'%Y-%m-%d') = '" . $searchTime . "' ");
        }
        if (isset($searchStartTime)) {
            $formatStr = '%Y-%m-%d';
            if (strlen($searchStartTime) > 10) {
                $formatStr = "%Y-%m-%d %H:%i";
            }
            $generationOperation->set_custom_where(" and DATE_FORMAT(add_time, '" . $formatStr . "') >= '" . $searchStartTime . "' ");
        }
        if (isset($searchEndTime)) {
            $formatStr = '%Y-%m-%d';
            if (strlen($searchEndTime) > 10) {
                $formatStr = "%Y-%m-%d %H:%i";
            }
            $generationOperation->set_custom_where(" and DATE_FORMAT(add_time, '" . $formatStr . "') <= '" . $searchEndTime . "' ");
        }

        if (!in_array($login_userid, array(1, 16, 161, 163))) {
            $generationOperation->set_where_and(P_GenerationOperation_soft::$field_customer_id, SqlOperator::Equals, $login_userid);
        }

        if (isset($sort) && isset($sortname)) {
            $generationOperation->set_order_by($generationOperation->get_field_by_name($sortname), $sort);
        } else {
            $generationOperation->set_order_by(P_GenerationOperation_soft::$field_add_time, 'DESC');
        }
        $generationOperation->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $generationOperation, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取统计资料失败，请重试');
        $models = Model::list_to_array($result['models']);
        echo_list_result($result, $models);
    }
    if ($action == 5) {
        $generationOperation = new P_GenerationOperation_soft();
        $generationOperation->set_custom_where(" AND ( qq like '%" . $qq . "%' OR platform_num like '%" . $qq . "%' ) ");
        $db = create_pdo();
        $generationOperation_result = Model::query_list($db, $generationOperation);
        $generationOperation_list = Model::list_to_array($generationOperation_result['models']);
        echo_result($generationOperation_list);
    }
    if ($action == 10) {
        $startTime = request_string("start_time");
        $endTime = request_string("end_time");
        $expolt = new ExportData2Excel();
        $generationOperation = new P_GenerationOperation_soft();
        if (isset($startTime)) {
            $generationOperation->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $generationOperation->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d') <= '" . $endTime . "' ");
        }
        $generationOperation->set_query_fields(array('add_time', 'platform_num', 'qq', 'sales_numbers', 'payment_amount', 'isArrears', 'platform_sales', 'customer', 'headmaster', 'payment_method', 'share_performance'));
        $db = create_pdo();
        $result = Model::query_list($db, $generationOperation, NULL, true);
        if (!$result[0]) {
            $expolt->create(array('导出错误'), array(array('代运营业绩数据导出失败,请稍后重试!')), "平台业绩数据导出", "平台业绩");
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $d['isArrears'] = $d['isArrears'] === 0 ? "否" : '是';
                });
        $title_array = array('添加时间', '平台号', 'QQ', '日销单数', '付款金额', '是否欠款', '平台销售', '售后名称', '班主任', '支付方式', '分享所得业绩');
        $expolt->create($title_array, $models, "代运营业绩数据导出", "代运营业绩");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $generationOperationData = request_object();
    //添加
    if ($action == 1) {
        $generationOperation = new P_GenerationOperation_soft();
        $generationOperation->set_field_from_array($generationOperationData);
        if (($generationOperationData->customer_id) === 0) {
            $generationOperation->set_customer_id(request_login_userid());
            $generationOperation->set_customer(request_login_username());
        }
        $generationOperation->set_add_time('now');
        $db = create_pdo();
        $result = $generationOperation->insert($db);
        if (!$result[0]) die_error(USER_ERROR, "添加代运营信息失败~");
        echo_msg("添加代运营信息成功~");
    }
    //删除
    if ($action == 2) {
        $generationOperation = new P_GenerationOperation_soft($generationOperationData->id);
        $db = create_pdo();
        $result = $generationOperation->delete($db);
        if (!$result[0]) die_error(USER_ERROR, '删除代运营信息失败');
        echo_msg('删除代运营信息成功~');
    }
    //修改信息
    if ($action == 3) {
        $generationOperation = new P_GenerationOperation_soft($generationOperationData->id);
        $fuck_array = array(1, 161, 163, 178);
        $login_user_id = request_login_userid();
        $db = create_pdo();
        if (!in_array($login_user_id, $fuck_array)) {
            $result = $generationOperation->load($db, $generationOperation);
            if (!$result[0]) die_error(USER_ERROR, '系统错误,请稍后重试~');
            if ($generationOperation->get_is_edit() == 1) {
                die_error(USER_ERROR, '该数据修改次数已上限,暂不能修改~');
            }
        }
        $generationOperation->reset();
        $generationOperation->set_field_from_array($generationOperationData);
        $generationOperation->set_id($generationOperationData->id);
        $generationOperation->set_is_edit(1);
        $result = $generationOperation->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存统计资料失败');
        echo_msg('保存成功');
    }
});
