<?php

/**
 * 添加补欠款
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/3/16
 */
use Models\Base\Model;
use Models\p_platform;
use Models\p_physica;
use Models\p_decoration;
use Models\Base\SqlOperator;
use Models\p_fills_second;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../common/http.php';
require '../../api/sale/update_customerstatistics.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $login_userid = request_login_userid();
    if ($action == 1) {
        $fillarrears = new p_fills_second();
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');
        $type = request_int('searchType');
        if (isset($searchName)) {
            $fillarrears->set_custom_where(" AND ( qq like '%" . $searchName . "%' OR ww like '%" . $searchName . "%' OR name LIKE '%" . $searchName . "%' OR add_name like'%" . $searchName . "%' ) ");
        }
        if (isset($type)) {
            $fillarrears->set_where_and(p_fills_second::$field_type, SqlOperator::Equals, $type);
        }
        if (isset($sort) && isset($sortname)) {
            $fillarrears->set_order_by($fillarrears->get_field_by_name($sortname), $sort);
        } else {
            $fillarrears->set_order_by(p_fills_second::$field_id, 'desc');
        }
        $fillarrears->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $fillarrears, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取补欠款资料失败，请重试');
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($login_userid) {
                    //只有实物和装修需要审核
                    $d['is_manager'] = in_array($login_userid, array(1, 35, 37, 435));
                });
        echo_list_result($result, $models);
    }
    if ($action == 11) {
        $export = new ExportData2Excel();
        $fillarrears = new p_fills_second();
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $type = request_int("type");
        if (isset($startTime)) {
            $startTime = date("Y-m-d 03:00:00", strtotime($startTime));
            $fillarrears->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d %H:%i:%s') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $endTime = date("Y-m-d 03:00:00", strtotime('+1 day', strtotime($endTime)));
            $fillarrears->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d %H:%i:%s') <= '" . $endTime . "' ");
        }
        if (isset($type)) {
            $fillarrears->set_where_and(p_fills_second::$field_type, SqlOperator::Equals, $type);
        }
        $field = array('add_time', 'type', 'ww', 'qq', 'name', 'mobile', 'play_price', 'fill_sum', 'tra_num', 'payment_method', 'customer', 'add_name', 'remark');
        $fillarrears->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $fillarrears, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('补欠款数据导出失败,请稍后重试!')), "补欠款数据导出", "补欠款数据");
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $d['tra_num'] = $d['tra_num'] . " ";
                    switch ((int) $d['type']) {
                        case 1:
                            $d['type'] = "平台业绩";
                            break;
                        case 2:
                            $d['type'] = "实物业绩";
                            break;
                        case 3:
                            $d['type'] = "装修业绩";
                            break;
                        case 4:
                            $d['type'] = "代运营";
                            break;
                    }
                    
                    //-----add  by kb @ 2016-03-12- start--
                     $name=trim($d['name']);
                    $len=mb_strlen($name,'utf-8');
                    //$first=mb_substr($name,0,1,"utf-8");
                   // $end=mb_substr($name,$len-1,1,"utf-8");
                   // $d['showname']=$first."**".$end;
                   
                   
                     $end=mb_substr($name,1,$len,"utf-8");
                     $d['showname']="*".$end;
                    
                    //-----add  by kb @ 2016-03-12- end--
                    
                });
        $field = array('add_time', 'type', 'ww', 'qq', 'name','showname', 'mobile', 'play_price', 'fill_sum', 'tra_num', 'payment_method', 'customer', 'add_name', 'remark');
        $title_array = array('添加时间', '类型', '旺旺', 'QQ', '姓名', '支付对应姓名', '手机号', '付款金额', '补欠款金额', '交易订单号', '收款方式', '售后', '添加人', '备注');
        $export->set_field($field);
        $export->set_field_width(array(20, 10, 20, 20, 10, 13, 10, 15, 15, 10, 15, 10, 20));
        $export->create($title_array, $models, "补欠款数据导出", "补欠款");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $fillarrearsData = request_object();
    //添加补欠款
    if ($action == 1) {
        $fillarrears = new p_fills_second();
        $fillarrears->set_field_from_array($fillarrearsData);
        $date = (!str_equals($fillarrearsData->add_time, "")) ? $fillarrearsData->add_time : date('Y-m-d H:i:s');
        $fillarrears->set_add_time($date);
        $fillarrears->set_add_name(request_username());
        $fillarrears->set_add_name_id(request_userid());
        $fillarrears->set_parent_id($fillarrearsData->parent_id);
        $mode_ = array(1 => new p_platform(), 2 => new p_physica(), 3 => new p_decoration())[$fillarrearsData->type];
        $db = create_pdo();
        pdo_transaction($db, function($db) use($mode_, $fillarrears) {
            $result = $fillarrears->insert($db);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '添加补欠款失败~' . $result['detail_cn'], $result);
            $mode_->set_status(1);
            $mode_->set_where_and($mode_->get_field_by_name('id'), SqlOperator::Equals, $fillarrears->get_parent_id());
            $result1 = $mode_->update($db, true);
            if (!$result1[0]) throw new TransactionException(PDO_ERROR_CODE, '添加补欠款失败~' . $result1['detail_cn'], $result1);
        });
        //只有流量业绩在新增补欠款时就计入售后统计，装修和实物需要审核
        if ($fillarrearsData->type == 1) {
            $action = 3;
            $ind_refund_rate = 0;
            $date = 0;
            update_customerstatistics($db, $fillarrearsData->customer_id, $fillarrearsData->fill_sum, $action, $date, $ind_refund_rate);
        }
        
        if($fillarrearsData->type ==2 ||$fillarrearsData->type ==3){
                //添加后向创想范人员发送审核消息
                $text = "亲，您有一笔二销业绩需要审核，请及时处理！";
                $msg = array('title' => "二销业绩审核消息", 'addtime' => date("Y-m-d H:i:s"), 'text' => $text, 'Code' => 0, 'Msg' => '', 'MsgType' => 5);
                send_push_msg(json_encode($msg), 435);
        }
        echo_msg('添加成功');
    }
    //修改销售统计信息
    if ($action == 2) {
        $fuck_array = array(1, 35, 37);
        $login_user_id = request_login_userid();
        $db = create_pdo();
        $fillarrears = new p_fills_second($fillarrearsData->id);
        $fillarrears->load($db, $fillarrears);
        $res_old = $fillarrears->to_array();
        $fill_sum = !$fillarrearsData->isTe ? (($fillarrearsData->fill_sum) - $res_old['fill_sum']) : $res_old['fill_sum'];
        $date = $res_old['add_time'];
        $date_post = $fillarrearsData->add_time;
        $isTe = $res_old['isTe'];
        if (!$fillarrearsData->isTe && !in_array($login_user_id, $fuck_array)) {
            if (!is_today($date)) die_error(USER_ERROR, '只能修改当天数据~');
        }
        //只有审核过的才进入售后统计
        if (($fillarrearsData->isTe || $isTe) || $res_old['type'] == 1) {
            switch ($res_old['type']) {
                case 1:
                    $action = 3;
                    break;
                case 2:
                    $action = 4;
                    break;
                case 3:
                    $action = 5;
                    break;
            }
        if($date == $date_post){
                 update_customerstatistics($db, $fillarrearsData->customer_id, $fill_sum, $action, $date);
            }
           else{
                 update_customerstatistics($db, $fillarrearsData->customer_id, -$res_old['fill_sum'], $action, $date);
                 update_customerstatistics($db, $fillarrearsData->customer_id, $fillarrearsData->fill_sum, $action, $date_post);
           }
        }
        $fillarrears->reset();
        $fillarrears->set_field_from_array($fillarrearsData);
        $fillarrears->set_id($fillarrearsData->id);
        $result = $fillarrears->update($db, true);
        //如果是添加记录的售后修改影响的行数等于1，表示这次编辑修改了数据
        if($fillarrears->customer_id == request_login_userid() && $result[1]){
            $fillarrears->set_is_edit(1);
            $result = $fillarrears->update($db, true);
        }
        if (!$result[0]) die_error(USER_ERROR, '保存统计资料失败');
        echo_msg('保存成功');
    }
    //删除
    if ($action == 3) {
        $db = create_pdo();
        $ind_refund_rate = 0;
        switch ($fillarrearsData->type) {
            case 1:
                $action = 3;
                $ind_refund_rate = -1;
                break;
            case 2:
                $action = 4;
                break;
            case 3:
                $action = 5;
                break;
        }
        $fillarrears_old = new p_fills_second($fillarrearsData->id);
        $fillarrears_old->load($db, $fillarrears_old);
        $res_old = $fillarrears_old->to_array();
        $date = $res_old['add_time'];
        $isTe = $res_old['isTe'];
        if ($isTe || $fillarrearsData->type == 1) {
            update_customerstatistics($db, $fillarrearsData->customer_id, -($fillarrearsData->fill_sum), $action, $date, $ind_refund_rate);
        }

        $fillarrears = new p_fills_second();
        $fillarrears->set_field_from_array($fillarrearsData);
        $result = $fillarrears->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除失败');
        echo_msg('删除成功');
    }
});

