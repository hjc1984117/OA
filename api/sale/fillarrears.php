<?php

/**
 * 补欠款
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/3/16
 */
use Models\Base\Model;
use Models\P_Salecount;
use Models\Base\SqlOperator;
use Models\P_Customerrecord;
use Models\P_Fills;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../api/sale/update_customerstatistics.php';
require '../../api/sale/update_salestatistics.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $db = create_pdo();
    if (!isset($action)) $action = -1;
    if ($action == 1) {
        $fillarrears = new P_Fills();
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');
        if (isset($searchName)) {
            $fillarrears->set_custom_where(" AND ( ww like '%" . $searchName . "%' OR name LIKE '%" . $searchName . "%' OR nick_name LIKE  '%" . $searchName . "%' OR add_name like'%" . $searchName . "%' ) ");
        }
        if (isset($sort) && isset($sortname)) {
            $fillarrears->set_order_by($fillarrears->get_field_by_name($sortname), $sort);
        } else {
            $fillarrears->set_order_by(P_Salecount::$field_id, 'desc');
        }
        $fillarrears->set_limit_paged(request_pageno(), request_pagesize());
        $result = Model::query_list($db, $fillarrears, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取补欠款资料失败，请重试');
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
        echo_list_result($result, $models, array('customer_list' => $customer_list, 'current_date' => date('Y-m-d H:i:s'), 'is_manager' => is_manager($login_userid)));
    }

    if ($action == 11) {
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $fills = new P_Fills();
        if (isset($startTime)) {
            $fills->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $fills->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d') <= '" . $endTime . "' ");
        }
        $field = array('add_time', 'ww', 'name', 'mobile', 'fill_sum', 'payment', 'channel', 'customer','add_name');
        $fills->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $fills, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('补欠款数据导出失败,请稍后重试!')), "补欠款数据导出", "补欠款");
        }
        $models = Model::list_to_array($result['models']);
        $title_array = array('日期', '旺旺号', '真实姓名', '手机号', '补欠金额', '收款方式', '接入渠道', '售后老师','添加人');
        $export->set_field($field);
        $export->create($title_array, $models, "补欠款数据导出", "补欠款");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $fillarrearsData = request_object();
    //添加补欠款
    if ($action == 1) {
        $money = 0;
        //补欠款分三种情况：1对应的销售记录有老师。2对应的销售没有老师，在补欠款自动分配的老师。3对应的销售没有老师，在补欠款通过手动下拉选择售后的方式
        $isupdate = 0;
        $fillarrears = new P_Fills();
        $fillarrears->set_field_from_array($fillarrearsData);
        if (!isset($fillarrearsData->add_name)) {
            $fillarrears->set_add_name(request_username());
        }
        $date = $fillarrearsData->add_time==''?date("Y-m-d H:i:s"):$fillarrearsData->add_time;
        $fillarrears->set_add_time($date);
        $db = create_pdo();
        $salecount = new P_Salecount($fillarrearsData->sale_id);
        $salecount_result = $salecount->load($db, $salecount);
       
        if (!$salecount_result[0]) {
            die_error(USER_ERROR, "系统错误,请稍后重试~");
        } else {
            //补欠款对应的那笔销售记录还没有分配售后
            if ($fillarrearsData->customer_id == 0) {             
                $money = $salecount->get_money();
                $isupdate = 1;
                $customer = new P_Customerrecord();
                $sql = "SELECT pc.id,pc.userid,pc.username,pc.nickname,pc.toplimit,IFNULL(ps.finish,0) AS finish ,pc.`status`,pc.lastDistribution,pc.qqReception,pc.starttime,pc.endtime ";
                $sql .= "FROM P_Customerrecord pc ";
                $sql .= "LEFT JOIN ( ";
                $sql .= "SELECT sa.customer,sa.customer_id, IFNULL(COUNT(sa.customer_id),0) AS finish FROM P_Salecount sa WHERE " . getWhereSql("sa") . " AND sa.customer_id != 0 GROUP BY sa.customer_id ";
                $sql .= ") AS ps ON pc.userid = ps.customer_id ";
                $sql .= "WHERE pc.toplimit > IFNULL(ps.finish,0) AND pc.`status` = 1 ";
                if ($salecount->get_isQQTeach() == 1) {//QQ教学 指定分配
                    $sql .= " AND pc.qqReception = 1 ";
                }
                if ($salecount->get_isTmallTeach_qj() == 1) {//旗舰 指定分配
                    $sql .= " AND pc.tmallReception_qj = 1 ";
                }
                if ($salecount->get_isTmallTeach_zy() == 1) {//专营店 指定分配
                    $sql .= " AND pc.tmallReception_zy = 1 ";
                }
                $sql .= " ORDER BY pc.lastDistribution ASC ";
                $customerres = Model::query_list($db, $customer, $sql);
                $models = Model::list_to_array($customerres['models']);
                if ($customerres['count'] != 0) {
                    $user = $models[0];
                    $customer = $user['username'];
                    $customer_id = $user['userid'];
                    $nick_name = $user['nickname'];
                    $salecount->set_customer($customer);
                    $salecount->set_customer_id($customer_id);
                    $salecount->set_nick_name($nick_name);
                    $salecount->set_customer_date('now');
                    $fillarrears->set_customer($customer);
                    $fillarrears->set_customer_id($customer_id);
                    $fillarrears->set_nick_name($nick_name);
                } else {
                    die_error(USER_ERROR, '暂无售后,请稍后重试~');
                }
                pdo_transaction($db, function($db) use($fillarrears, $salecount) {
                    $result_fillarrears = $fillarrears->insert($db);
                    if (!$result_fillarrears[0]) throw new TransactionException(PDO_ERROR_CODE, '添加补欠款失败。' . $result_fillarrears['detail_cn'], $result_fillarrears);
                    $result_salecount = $salecount->update($db, true);
                    if (!$result_salecount[0]) throw new TransactionException(PDO_ERROR_CODE, '添加补欠款失败。' . $result_salecount['detail_cn'], $result_salecount);
                });
            } else {
                if ($salecount->get_customer_id() !== $fillarrears->get_customer_id()) {
                    $salecount->set_customer($fillarrears->get_customer());
                    $salecount->set_customer_id($fillarrears->get_customer_id());
                    $salecount->set_nick_name($fillarrears->get_nick_name());
                    $salecount->set_customer_date('now');
                    $money = $salecount->get_money();
                    $isupdate = 1;
                    pdo_transaction($db, function($db) use($fillarrears, $salecount) {
                        $result_fillarrears = $fillarrears->insert($db);
                        if (!$result_fillarrears[0]) throw new TransactionException(PDO_ERROR_CODE, '添加补欠款失败。' . $result_fillarrears['detail_cn'], $result_fillarrears);
                        $result_salecount = $salecount->update($db, TRUE);
                        if (!$result_salecount[0]) throw new TransactionException(PDO_ERROR_CODE, '添加补欠款失败。' . $result_salecount['detail_cn'], $result_salecount);
                    });
                } else {
                    $result = $fillarrears->insert($db);
                    if (!$result[0]) die_error(USER_ERROR, '添加补欠款失败。');
                }
            }
            update_salestatistics($db, $fillarrears->get_add_name_id(), $fillarrears->get_add_name());
            //补欠款对应的那笔销售记录原本就分配了售后，或者原本没有分配售后，在补欠款的时候自动分配成功了
            if($fillarrearsData->customer_id || $customerres['count'] != 0){
                $action = 1;
                $ind_refund_rate = 0;    
                update_customerstatistics($db, $fillarrears->get_customer_id(), $fillarrearsData->fill_sum+$money,$action,$date,$ind_refund_rate,$isupdate);
            }
            
            echo_msg('添加成功');
        }
    }
    
    if ($action == 2) {       
        $db = create_pdo();    
        $fillarrears_old = new P_Fills($fillarrearsData->id);
        $fillarrears_old->load($db, $fillarrears_old);
        $res_old = $fillarrears_old->to_array();
        $fill_sum = ($fillarrearsData->fill_sum)-$res_old['fill_sum'];
        $customer_id = $res_old['customer_id'];
        $date = $res_old['add_time'];
        $action = 1;
        update_customerstatistics($db,$customer_id,$fill_sum,$action,$date);
        
        $fillarrears = new P_Fills();
        $fillarrears->set_field_from_array($fillarrearsData);
        $result = $fillarrears->update($db, true);   
        if (!$result[0]) die_error(USER_ERROR, '保存统计资料失败');
        echo_msg('保存成功');
    }
    //删除
    if ($action == 3) {        
        $db = create_pdo();
        //删除操作影响到对应售后人员的售后统计记录
        $fillarrears_old = new P_Fills($fillarrearsData->id);
        $fillarrears_old->load($db, $fillarrears_old);
        $res_old = $fillarrears_old->to_array();
        $date = $res_old['add_time'];
        $action = 1;
        update_customerstatistics($db, $fillarrearsData->customer_id, -($fillarrearsData->fill_sum),$action,$date);
        
        $fillarrears = new P_Fills();
        $fillarrears->set_field_from_array($fillarrearsData);
        $result = $fillarrears->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除失败');
        
        echo_msg('删除成功');
    }
});

