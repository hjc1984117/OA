<?php

/**
 * 退款记录
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/5/14
 */
use Models\Base\Model;
use Models\P_Refund_soft;
use Models\P_Salecount_soft;
use Models\p_refundapply_soft;
use Models\Base\SqlOperator;
use Models\Base\SqlSortType;
use Models\P_Customerdetails_soft;
use \Models\p_customerrecord_soft;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../Common/http.php';
require '../../api/sale_soft/update_customerstatistics.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $db = create_pdo();
    if (!isset($action)) $action = -1;
    if ($action == 1) {
        $refund = new P_Refund_soft();
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');
        $searchTime = request_string('searchTime');
        $refund_type = request_string("refund_type");
        $login_userid = request_login_userid();
        $is_manager = is_manager($login_userid, 2);
        if (isset($searchName)) {
            $refund->set_custom_where(" AND ( presale like '%" . $searchName . "%' "
                    . "OR name LIKE '%" . $searchName . "%' "
                    . "OR customer like'%" . $searchName . "%' "
                    . "OR ww LIKE '%" . $searchName . "%' "
                    . "OR status LIKE '%" . $searchName . "%' "
                    . "OR duty LIKE '%" . $searchName . "%' "
                    . "OR controlman LIKE '%" . $searchName . "%' ) ");
        }
        if (isset($searchTime)) {
            $refund->set_custom_where(" AND DATE_FORMAT(date, '%Y-%m-%d') = '" . $searchTime . "'");
        }
        if (isset($refund_type)) {
            $refund->set_where_and(P_Refund_soft::$field_refund_type, SqlOperator::Equals, $refund_type);
        }
        if (!$is_manager) {
            $refund->set_custom_where(" and ( customer_id = " . $login_userid . " or presale_id = " . $login_userid . " ) ");
        }
        if (isset($sort) && isset($sortname)) {
            $refund->set_order_by($refund->get_field_by_name($sortname), $sort);
        } else {
            $refund->set_order_by(P_Refund_soft::$field_id, 'desc');
        }
        $refund->set_limit_paged(request_pageno(), request_pagesize());
        $result = Model::query_list($db, $refund, NULL, true);
        if (!$result[0]) {
            die_error(USER_ERROR, '获取统计资料失败，请重试');
        }
        $models = Model::list_to_array($result['models']);
        echo_list_result($result, $models);
    }
    if ($action == 11) {
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $sql = "select a.date,a.ww,b.mobile,a.setmeal,a.refund_type,a.`status`,b.money,a.money AS tk_money,a.recordMoney,a.retrieve,a.refund_rate,a.ind_refund_rate,a.add_user,a.presale,a.customer,a.duty,a.refund_shop,a.remark,a.reason,a.is_logoff_dbb ";
        $sql .=" from P_Refund_soft as a right join P_Salecount_soft as b on a.s_id = b.id where 1=1 ";
        if (isset($startTime)) {
            $sql.=" and DATE_FORMAT(a.date, '%Y-%m-%d') >= '" . $startTime . "'";
        }
        if (isset($endTime)) {
            $sql .=" and DATE_FORMAT(a.date, '%Y-%m-%d') <= '" . $endTime . "' ";
        }
        $sql .= "order by a.id desc";
        $db = create_pdo();
        $result = Model::execute_custom_sql($db, $sql);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('退款记录数据导出失败,请稍后重试!')), "退款记录数据导出", "退款记录");
        }
        $models = $result['results'];
        array_walk($models, function (&$d) {
            $d['customer'] = $d['customer2'] ? $d['customer2'] : $d['customer'];
        });
        $title_array = array('退款日期', '旺旺', '手机号', '版本', '套餐金额', '记录金额', '退款金额', '挽回金额', '退款类型', '实际退款笔数', '个人退款笔数', '操作人员', '实际责任', '退款方式', '退款原因', '退款店铺', '售前责任人', '售后责任人', '是否注销', '备注');
        $field = array('date', 'ww', 'mobile', 'setmeal', 'money', 'recordMoney', 'tk_money', 'retrieve', 'refund_type', 'refund_rate', 'ind_refund_rate', 'add_user', 'duty', 'status', 'reason', 'refund_shop', 'presale', 'customer', 'is_logoff_dbb', 'remark');
        $export->set_field($field);
        $export->set_field_width(array(15, 20, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15, 12, 12, 15, 15, 15, 15, 30));
        $export->create($title_array, $models, "退款记录数据导出", "退款记录");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $refundData = request_object();
    //添加退款
    if ($action == 1) {
        $refund = new P_Refund_soft();
        $refund->set_field_from_array($refundData);
        if (!isset($refundData->date) || str_equals(($refundData->date), "")) {
            $refund->set_date("now");
        }
        $db = create_pdo();
        $salecount = new P_Salecount_soft($refundData->s_id);
        $result_salecount = $salecount->load($db, $salecount);
        if (!$result_salecount[0]) die_error(USER_ERROR, '保存退款信息失败~');
        $refund->set_presale($salecount->get_presales());
        $refund->set_presale_id($salecount->get_presales_id());  
        $refund->set_customer($salecount->get_customer());
        $refund->set_customer_id($salecount->get_customer_id());      
        $refund->set_name($salecount->get_name());
        $refund->set_ww($salecount->get_ww());
        $refund->set_totalmoney($salecount->get_money());
        $refund->set_setmeal($salecount->get_setmeal());
        $refund->set_refund_shop($salecount->get_c_shop());

        pdo_transaction($db, function($db) use($refund, $refundData) {
            $result = $refund->insert($db);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存退款信息失败~' . $result['detail_cn'], $result);
            add_data_add_log($db, $refundData, new P_Refund_soft($refund->get_id()), 10);
            $refund_id = $refund->get_s_id();
            $customerdetails = new P_Customerdetails_soft();
            $customerdetails->set_where_and(P_Customerdetails_soft::$field_sale_id, SqlOperator::Equals, $refund_id);
            $result_customerdetails = $customerdetails->load($db, $customerdetails);
            if (!$result_customerdetails[0]) throw new TransactionException(PDO_ERROR_CODE, '保存退款信息失败~' . $result_customerdetails['detail_cn'], $result_customerdetails);
            $customerdetails->set_is_refund(1);
            $customerdetails_update_result = $customerdetails->update($db, true);
            if (!$customerdetails_update_result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存退款信息失败~' . $customerdetails_update_result['detail_cn'], $customerdetails_update_result);
        });
        $date = $refundData->date==''?date("Y-m-d H:i:s"):$refundData->date;
        $res = $salecount->to_array();
        if ($refundData->duty == '售后') {
             $customer_id = $res['customer_id'];
             $action = 2;
             update_customerstatistics($db, $customer_id, $refundData->recordMoney, $action, $date,$refundData->ind_refund_rate);
        }
        echo_msg('添加成功');
    }
    //修改退款
    if ($action == 2) {
        $db = create_pdo();
        $refund_old = new P_Refund_soft($refundData->id);
        $refund_old->load($db, $refund_old);
        $res = $refund_old->to_array();
        $date = $res['date'];
        $date_post = $refundData->date;
        $customerrecord = new p_customerrecord_soft();
        $customerrecord->set_where_and(p_customerrecord_soft::$field_username, SqlOperator::Equals, $res['customer']);
        $customerrecord->load($db,$customerrecord);
        $customerrecord_res = $customerrecord->to_array();
        $customer_id = $customerrecord_res['userid'];
        $action = 2;
        if($res['duty'] == '售后')
        {
            if($refundData->duty == '售后')
                {                  
                    if($date != $date_post){
                        update_customerstatistics($db, $customer_id, -$res['recordMoney'], $action, $date,-$res['ind_refund_rate']);
                        update_customerstatistics($db, $customer_id, $refundData->recordMoney, $action, $date_post,$refundData->ind_refund_rate);
                        $money = 0;
                        $ind_refund_rate = 0;
                    }
                    else{
                         $money = ($refundData->recordMoney)-$res['recordMoney'];  
                         $ind_refund_rate = ($refundData->ind_refund_rate)-$res['ind_refund_rate'];
                    }
                }
            else
                {
                    $money = 0-($refundData->recordMoney);
                    $ind_refund_rate = 0-($refundData->ind_refund_rate);
                }
        }
        else 
        {
            if ($refundData->duty == '售后') 
                {
                    $money = ($refundData->recordMoney);
                    $ind_refund_rate = ($refundData->ind_refund_rate);
                    $date = $date_post;
                } 
            else 
                {
                    $money = 0;
                    $ind_refund_rate = 0;
                }
        }      
        update_customerstatistics($db, $customer_id, $money, $action, $date,$ind_refund_rate);
        
        $refund_old = new P_Refund_soft($refundData->id);
        $refund_old->load($db, $refund_old);       
        $refund_old->set_field_from_array($refundData);
        $totalmoney = $refund_old->get_totalmoney();
        $money = $refund_old->get_money();
        $refund_old->set_retrieve($totalmoney - $money);
        $result = $refund_old->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存退款资料失败');
        add_data_change_log($db, $refundData, new P_Refund_soft($refund_old->get_id()), 10);
        echo_msg('保存成功');      
    }
    //删除退款
    if ($action == 3) {
        $db = create_pdo();
        $refund_old = new P_Refund_soft($refundData->id);       
        $refund_old->load($db, $refund_old);
        $res = $refund_old->to_array();      
        $date = $res['date'];
        $customerrecord = new p_customerrecord_soft();
        $customerrecord->set_where_and(p_customerrecord_soft::$field_username, SqlOperator::Equals, $res['customer']);
        $customerrecord->load($db,$customerrecord);
        $customerrecord_res = $customerrecord->to_array();
        $customer_id = $customerrecord_res['userid'];
        if($res['duty'] == '售后'){
                $money = 0-($refundData->recordMoney);
                $ind_refund_rate = 0-($refundData->ind_refund_rate);
        }else{
                $money = 0;
                $ind_refund_rate = 0;
        }
        $action = 2;
        update_customerstatistics($db, $customer_id, $money, $action, $date,$ind_refund_rate);
        
        $refund = new P_Refund_soft();
        $refund->set_field_from_array($refundData);
        $result = $refund->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除失败');
        echo_msg('删除成功');
    }
});
