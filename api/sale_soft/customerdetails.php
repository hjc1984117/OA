<?php

/**
 * 客户详情
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/7/22
 */
use Models\Base\Model;
use Models\P_Customerdetails_soft;
use Models\P_Salecount_soft;
use Models\Base\SqlOperator;
use Models\Base\SqlSortType;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../Common/http.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {

    $login_userid = request_login_userid();
    $manager_role_ids = array(0, 101, 102, 103, 401, 402, 403, 404, 601, 602, 701, 702, 801, 802, 901, 902, 1101, 1102, 1103, 1112);
    $manager_userids = array(187);
    $is_manager = in_array(get_role_id(), $manager_role_ids);
    if (!isset($action)) $action = -1;
    if ($action == 1) {
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');
        $searchTime = request_string('searchTime');
        //advance
        $search_startdate = request_string('startDueDate');
        $search_enddate = request_string('endDueDate');
        $search_ww = request_string('ww');
        $search_qq = request_string('qq');
        $search_payment = request_string('payment');
        $search_is_receive = request_string('is_receive');
        $search_is_reviews = request_string('is_reviews');
        $search_is_addto_reviews = request_string('is_addto_reviews');
        $search_is_add_qq = request_string('is_add_qq');
        $search_is_added = request_string('is_added');
        $search_is_refund = request_string('is_refund');
        $search_is_two_sales = request_string('is_two_sales');
        //advance
        $customer = new P_Customerdetails_soft();
        if (isset($searchName)) {
            $customer->set_custom_where(" AND ( presales like '%" . $searchName . "%' "
                    . "OR customer like '%" . $searchName . "%' "
                    . "OR qq like '%" . $searchName . "%' "
                    . "OR ww LIKE '%" . $searchName . "%' ) ");
        }
        if (isset($searchTime)) {
            $customer->set_custom_where(" AND DATE_FORMAT(date, '%Y-%m-%d') = '" . $searchTime . "'");
        }
        if (isset($search_startdate)) {
            $customer->set_custom_where(" AND DATE_FORMAT(date,'%Y-%m-%d') >= '" . $search_startdate . "' ");
        }
        if (isset($search_enddate)) {
            $customer->set_custom_where(" AND DATE_FORMAT(date,'%Y-%m-%d') <= '" . $search_enddate . "' ");
        }
        if (isset($search_ww)) {
            $customer->set_where_and(P_Customerdetails_soft::$field_ww, SqlOperator::Like, '%' . $search_ww . '%');
        }
        if (isset($search_qq)) {
            $customer->set_where_and(P_Customerdetails_soft::$field_qq, SqlOperator::Like, '%' . $search_qq . '%');
        }
        if (isset($search_payment)) {
            $customer->set_where_and(P_Customerdetails_soft::$field_payment, SqlOperator::Like, '%' . $search_payment . '%');
        }
        if (isset($search_is_receive)) {
            $customer->set_where_and(P_Customerdetails_soft::$field_is_receive, SqlOperator::Equals, $search_is_receive);
        }
        if (isset($search_is_reviews)) {
            $customer->set_where_and(P_Customerdetails_soft::$field_is_reviews, SqlOperator::Equals, $search_is_reviews);
        }
        if (isset($search_is_addto_reviews)) {
            $customer->set_where_and(P_Customerdetails_soft::$field_is_addto_reviews, SqlOperator::Equals, $search_is_addto_reviews);
        }
        if (isset($search_is_add_qq)) {
            $customer->set_where_and(P_Customerdetails_soft::$field_is_add_qq, SqlOperator::Equals, $search_is_add_qq);
        }
        if (isset($search_is_added)) {
            $customer->set_where_and(P_Customerdetails_soft::$field_is_added, SqlOperator::Equals, $search_is_added);
        }
        if (isset($search_is_refund)) {
            $customer->set_where_and(P_Customerdetails_soft::$field_is_refund, SqlOperator::Equals, $search_is_refund);
        }
        if (isset($search_is_two_sales)) {
            $customer->set_where_and(P_Customerdetails_soft::$field_is_two_sales, SqlOperator::Equals, $search_is_two_sales);
        }
        if (isset($sort) && isset($sortname)) {
            $customer->set_order_by($customer->get_field_by_name($sortname), $sort);
        } else {
            $customer->set_order_by(P_Customerdetails_soft::$field_id, 'desc');
        }
        if (!$is_manager) {
            $customer->begin_where_group_condition();
            $customer->set_where_and(P_Customerdetails_soft::$field_presales_id, SqlOperator::Equals, $login_userid);
            $customer->set_where_or(P_Customerdetails_soft::$field_customer_id, SqlOperator::Equals, $login_userid);
            $customer->end_where_group_condition();
        }
        $customer->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $customer, NULL, true);
        if (!$result[0]) {
            die_error(USER_ERROR, '获取统计资料失败，请重试');
        }
        $models = Model::list_to_array($result['models']);
        echo_list_result($result, $models, array('is_manager' => $is_manager));
    }
    if ($action == 11) {
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $customer = new P_Customerdetails_soft();
        if (isset($startTime)) {
            $customer->set_custom_where(" and DATE_FORMAT(date, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $customer->set_custom_where(" and DATE_FORMAT(date, '%Y-%m-%d') <= '" . $endTime . "' ");
        }

        $field = array('date', 'ww', 'qq', 'money', 'payment', 'is_receive', 'is_reviews', 'is_addto_reviews', 'is_add_qq', 'is_added', 'is_refund', 'is_two_sales', 'remark');
        $customer->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $customer, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('客户详情数据导出失败,请稍后重试!')), "客户详情数据导出", "客户详情");
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $d['is_receive'] = $d['is_receive'] === 0 ? "否" : '是';
                    $d['is_reviews'] = $d['is_reviews'] === 0 ? "否" : '是';
                    $d['is_addto_reviews'] = $d['is_addto_reviews'] === 0 ? "否" : '是';
                    $d['is_add_qq'] = $d['is_add_qq'] === 0 ? "否" : '是';
                    $d['is_added'] = $d['is_added'] === 0 ? "否" : '是';
                    $d['is_refund'] = $d['is_refund'] === 0 ? "否" : '是';
                    $d['is_two_sales'] = $d['is_two_sales'] === 0 ? "否" : '是';
                });
        $title_array = array('日期', '旺旺', 'QQ', '金额', '收款方式', '收货', '评价', '追评', '可加Q', '已加上', '退款', '二销', '备注');
        $export->set_field($field);
        $export->create($title_array, $models, "客户详情数据导出", "客户详情");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $customerData = request_object();
    //添加客户详情
    if ($action == 1) {
        $customer = new P_Customerdetails_soft();
        $customer->set_field_from_array($customerData);
        if (!isset($customerData->date)) {
            $customer->set_date("now");
        }
        $db = create_pdo();
        $result = $customer->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加客户详情失败。');
        echo_msg('添加成功');
    }
    //修改客户详情
    if ($action == 2) {
        $customer = new P_Customerdetails_soft();
        $customer->set_field_from_array($customerData);
        $db = create_pdo();
        $result = $customer->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存客户详情资料失败');
        echo_msg('保存成功');
    }
    //删除客户详情
    if ($action == 3) {
        $customer = new P_Customerdetails_soft();
        $customer->set_field_from_array($customerData);
        $db = create_pdo();
        $result = $customer->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除失败');
        echo_msg('删除成功');
    }
});
