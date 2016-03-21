<?php

/**
 * 升级
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/4/20
 */
use Models\Base\Model;
use Models\P_Salecount;
use Models\Base\SqlOperator;
use Models\P_Customerrecord;
use Models\P_Upgrade;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../api/sale/update_customerstatistics.php';
require '../../api/sale/update_salestatistics.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if (!isset($action)) $action = -1;
    if ($action == 1) {
        $upgrade = new P_Upgrade();
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');
        if (isset($searchName)) {
            $upgrade->set_custom_where(" AND ( ww like '%" . $searchName . "%' OR name LIKE '%" . $searchName . "%' OR add_name like'%" . $searchName . "%' ) ");
        }
        if (isset($sort) && isset($sortname)) {
            $upgrade->set_order_by($upgrade->get_field_by_name($sortname), $sort);
        } else {
            $upgrade->set_order_by(P_Upgrade::$field_id, 'desc');
        }
        $upgrade->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $upgrade, NULL, true);
        if (!$result[0]) {
            die_error(USER_ERROR, '获取统计资料失败，请重试');
        }
        $models = Model::list_to_array($result['models']);
        $customer = new P_Customerrecord();
        $customer->set_status(1);
        $customer->set_query_fields(array('userid', 'username', 'nickname'));
        $customer_result = Model::query_list($db, $customer);
        $customer_list = Model::list_to_array($customer_result['models'], array(), function(&$d) {
                    $d['id'] = $d['userid'];
                    $d['text'] = $d['username'] . "(" . $d['nickname'] . ")";
                    unset($d['userid']);
                    unset($d['username']);
                    unset($d['nickname']);
                });
        $login_userid = request_login_userid();
        echo_list_result($result, $models, array('customer_list' => $customer_list, 'current_date' => date('Y-m-d'), 'is_manager' => is_manager($login_userid)));
    }
    if ($action == 11) {
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $upgrade = new P_Upgrade();
        if (isset($startTime)) {
            $upgrade->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $upgrade->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d') <= '" . $endTime . "' ");
        }
        $field = array('add_time', 'ww', 'name', 'mobile', 'upgrade_sum', 'payment', 'channel', 'customer', 'add_name');
        $upgrade->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $upgrade, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('升级记录数据导出失败,请稍后重试!')), "升级记录数据导出", "升级记录");
        }
        $models = Model::list_to_array($result['models']);
        $title_array = array('日期', '旺旺号', '真实姓名', '手机号', '升级金额', '收款方式', '接入渠道', '售后老师', '添加人');
        $export->set_field($field);
        $export->create($title_array, $models, "升级记录数据导出", "升级记录");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $upgradeData = request_object();
    //添加升级
    if ($action == 1) {
        $upgrade = new P_Upgrade();
        $upgrade->set_field_from_array($upgradeData);
        $date = $upgradeData->add_time==''?date("Y-m-d H:i:s"):$upgradeData->add_time;
        $upgrade->set_add_time($date);
        if (!isset($upgradeData->add_name)) {
            $upgrade->set_add_name(request_username());
        }
        $db = create_pdo();
        $result = $upgrade->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加升级失败。');
        update_salestatistics($db, $upgrade->get_add_name_id(), $upgrade->get_add_name());
        $action = 1;
        update_customerstatistics($db, $upgradeData->customer_id, $upgradeData->upgrade_sum,$action,$date);
        echo_msg('添加成功');
    }
    //修改
    if ($action == 2) {
        $db = create_pdo();
        $upgrade_old = new P_Upgrade($upgradeData->id);
        $upgrade_old->load($db, $upgrade_old);
        $res_old = $upgrade_old->to_array();
        $upgrade_sum = ($upgradeData->upgrade_sum)-$res_old['upgrade_sum'];
        $date = $res_old['add_time'];
        $action = 1;
        update_customerstatistics($db, $upgradeData->customer_id, $upgrade_sum, $action,$date);
        
        $upgrade = new P_Upgrade();
        $upgrade->set_field_from_array($upgradeData);
        $result = $upgrade->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存统计资料失败');
        echo_msg('保存成功');
    }
    //删除
    if ($action == 3) {      
        $db = create_pdo();
        $upgrade_old = new P_Upgrade($upgradeData->id);
        $upgrade_old->load($db, $upgrade_old);
        $res_old = $upgrade_old->to_array();
        $date = $res_old['add_time'];
        $action = 1;
        update_customerstatistics($db, $upgradeData->customer_id, -($upgradeData->upgrade_sum),$action,$date);
        
        $upgrade = new P_Upgrade();
        $upgrade->set_field_from_array($upgradeData);
        $result = $upgrade->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除失败');
       
        echo_msg('删除成功');
    }
});

