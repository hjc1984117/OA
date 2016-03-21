<?php

/**
 * 平台业绩
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/7/13
 */
use Models\Base\Model;
use Models\p_platform_soft;
use Models\Base\SqlOperator;
use Models\P_Customerrecord_second_soft;
use Models\P_Customerrecord_soft;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../common/http.php';
require '../../api/sale_soft/update_customerstatistics.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $login_userid = request_login_userid();
    $login_deptid = get_employees()[$login_userid]['dept1_id'];
    $manager_role_ids = array(0, 101, 102, 103);
    $manager_userids = array(1, 161, 163, 441);
    $is_manager = in_array(get_role_id(), $manager_role_ids) || in_array($login_userid, $manager_userids);

    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $keyWord = request_string("workName");
    $searchTime = request_string("searchTime");
    $searchStartTime = request_string("searchStartTime");
    $searchEndTime = request_string("searchEndTime");
    $qq = request_string("qq");
    if ($action == 1) {
        $platform = new p_platform_soft();
        if (isset($keyWord)) {
            $platform->set_custom_where(" AND (customer LIKE '%" . $keyWord . "%' OR ww LIKE '%" . $keyWord . "%' OR qq LIKE '%" . $keyWord . "%' OR name LIKE '%" . $keyWord . "%' OR phone LIKE '%" . $keyWord . "%' OR diamond_card LIKE '%" . $keyWord . "%' OR money LIKE '%" . $keyWord . "%' OR alipay_account LIKE '%" . $keyWord . "%' OR payment_method LIKE '%" . $keyWord . "%' OR tra_num LIKE '%" . $keyWord . "%') ");
        }
        if (isset($searchTime)) {
            $searchStartTime = date("Y-m-d 03:00:00", strtotime($searchTime));
            $searchEndTime = date("Y-m-d 03:00:00", strtotime('+1 day', strtotime($searchTime)));
            $platform->set_custom_where(" AND DATE_FORMAT(add_time,'%Y-%m-%d %H:%i:%s') >= '" . $searchStartTime . "' ");
            $platform->set_custom_where(" AND DATE_FORMAT(add_time,'%Y-%m-%d %H:%i:%s') <= '" . $searchEndTime . "' ");
        }
        if (isset($searchStartTime)) {
            $formatStr = '%Y-%m-%d';
            if (strlen($searchStartTime) > 10) {
                $formatStr = "%Y-%m-%d %H:%i";
            }
            $platform->set_custom_where(" and DATE_FORMAT(add_time, '" . $formatStr . "') >= '" . $searchStartTime . "' ");
        }
        if (isset($searchEndTime)) {
            $formatStr = '%Y-%m-%d';
            if (strlen($searchEndTime) > 10) {
                $formatStr = "%Y-%m-%d %H:%i";
            }
            $platform->set_custom_where(" and DATE_FORMAT(add_time, '" . $formatStr . "') <= '" . $searchEndTime . "' ");
        }
        $addWhere = !(in_array($login_userid, array(1, 16, 161, 163)) || in_array($login_deptid, array(4, 1)) || $is_manager);
        if ($addWhere) {
            $platform->set_where_and(p_platform_soft::$field_customer_id, SqlOperator::Equals, $login_userid);
        }
        if (isset($sort) && isset($sortname)) {
            $platform->set_order_by($platform->get_field_by_name($sortname), $sort);
        } else {
            $platform->set_order_by(p_platform_soft::$field_add_time, 'DESC');
        }
        $platform->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $platform, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取统计资料失败，请重试');
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($is_manager) {
                    $d['is_manager'] = $is_manager;
                });
        echo_list_result($result, $models);
    }
    if ($action == 2) {
        $customer = new P_Customerrecord_second_soft();
        $customer->set_status(1);
        $customer->set_query_fields(array('userid', 'username', 'nickname'));
        $db = create_pdo();
        $customer_result = Model::query_list($db, $customer);
        $customer_list = Model::list_to_array($customer_result['models'], array(), function(&$d) {
                    $d['id'] = $d['userid'];
                    $d['text'] = $d['username'] . "(" . $d['nickname'] . ")";
                    unset($d['userid']);
                    unset($d['username']);
                    unset($d['nickname']);
                });
        echo_result($customer_list);
    }
    if ($action == 5) {
        $platform = new p_platform_soft();
        $platform->set_query_fields(array('id', 'ww', 'qq', 'money', 'phone', 'payment_method')); // 'name', 
        $platform->set_custom_where(" AND ( qq like '%" . $qq . "%' OR ww like '%" . $qq . "%' OR phone like '%" . $qq . "%' ) "); // "%' OR name like '%" . $qq . 
        $db = create_pdo();
        $platform_result = Model::query_list($db, $platform);
        $platform_list = Model::list_to_array($platform_result['models']);
        echo_result($platform_list);
    }
    if ($action == 6) {
        $search_name = request_string("searchName");
        if (isset($search_name)) {
            $platform = new p_platform_soft();
            $platform->set_custom_where(" AND ( ww = '" . $search_name . "' OR alipay_account = '" . $search_name . "' OR name = '" . $search_name . "' OR phone = '" . $search_name . "' OR qq = '" . $search_name . "' ) ");
            $platform->set_query_fields(array('ww', 'alipay_account', 'name', 'qq', 'payment_method'));
            $db = create_pdo();
            $result = Model::query_list($db, $platform);
            if (!$result[0]) die_error(USER_ERROR, "查询失败,请稍后重试或手动录入~");
            $model = Model::list_to_array($result['models']);
            echo_result(array('code' => 0, 'model' => $model[0]));
        } else {
            echo_result(array('code' => 0, 'model' => array()));
        }
    }
    if ($action == 10) {
        $startTime = request_string("start_time");
        $endTime = request_string("end_time");
        $export = new ExportData2Excel();
        $platform = new p_platform_soft();
        if (isset($startTime)) {
            $startTime = date("Y-m-d 03:00:00", strtotime($startTime));
            $platform->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d %H:%i:%s') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $endTime = date("Y-m-d 02:59:59", strtotime('+1 day', strtotime($endTime)));
            $platform->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d %H:%i:%s') <= '" . $endTime . "' ");
        }
        $field = array('add_time', 'ww', 'qq', 'name', 'phone', 'money', 'diamond_card', 'p_arrears', 'alipay_account', 'payment_method', 'customer', 'tra_num');
        $platform->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $platform, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('平台业绩数据导出失败,请稍后重试!')), "平台业绩数据导出", "平台业绩");
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $d["tra_num"] = $d["tra_num"] . " ";
                    
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
       
        $field = array('add_time', 'ww', 'qq', 'name','showname', 'phone', 'money', 'diamond_card', 'p_arrears', 'alipay_account', 'payment_method', 'customer', 'tra_num');
             
        $title_array = array('日期', '旺旺名', 'QQ号', '转款人姓名', '支付对应姓名', '手机号码', '付款金额', '版本', '流量欠款', '客户支付宝', '收款方式', '售后名称', '交易/订单号');
        $export->set_field($field);
        $export->create($title_array, $models, "平台业绩数据导出", "平台业绩");
    }
    if ($action == 11) {
        $time_unit = request_int('time_unit', 1, 3);
        $tab = request_string('tab');
        if (!isset($tab)) {
            $tab = "1,2,3";
        }
        $tab = explode(",", $tab);
        $condition_mapping = array(
            1 => 'TO_DAYS(add_time) = TO_DAYS(NOW())',
            2 => 'add_time >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)',
            3 => "DATE_FORMAT(add_time,'%Y-%m') = DATE_FORMAT(NOW(),'%Y-%m')"
        );
        $sql = "SELECT a.customer,a.customer_id,SUM(a.count) count  FROM(";

        if (in_array(1, $tab)) {
            $sql .="SELECT * FROM (";
            $sql .="SELECT customer customer,customer_id customer_id,SUM(money) count FROM p_platform_soft WHERE " . $condition_mapping[$time_unit] . " GROUP BY customer ORDER BY COUNT(customer) DESC";
            $sql .=") p ";
            if (array_search(2, $tab) || array_search(3, $tab)) {
                $sql .="UNION ALL ";
            }
        }

        if (in_array(2, $tab)) {
            $sql .="SELECT * FROM (";
            $sql .="SELECT customer customer,customer_id customer_id,SUM(all_price) count FROM P_Physica_soft h WHERE " . $condition_mapping[$time_unit] . " GROUP BY customer ORDER BY COUNT(customer) DESC";
            $sql .=") h ";
            if (array_search(3, $tab)) {
                $sql .="UNION ALL ";
            }
        }

        if (in_array(3, $tab)) {
            $sql .="SELECT * FROM (";
            $sql .="SELECT customer customer,customer_id customer_id,SUM(decoration_price) count FROM P_Decoration_soft WHERE " . $condition_mapping[$time_unit] . " GROUP BY customer ORDER BY COUNT(customer) DESC";
            $sql .=") d ";
        }

        $sql .=")a GROUP BY a.customer_id ORDER BY SUM(a.count) desc";
        $db = create_pdo();
        $result = Model::execute_custom_sql($db, $sql);
        if (!$result[0]) die_error(USER_ERROR, '获取排名数据失败，请重试');
        $result = $result['results'];
        echo_result($result);
    }
    if ($action == 20) {
        $qk = request_string("qk");
        $platform = new p_platform_soft();
        //旺旺/QQ/真实姓名
        $platform->set_custom_where(" AND ( ww = '" . $qk . "' OR qq = '" . $qk . "' OR phone = '" . $qk . "' ) "); // "' OR name = '" . $qk . 
        $platform->set_query_fields(array('ww', 'qq', 'phone', 'money', 'alipay_account')); //'name', 
        $db = create_pdo();
        $result = Model::query_list($db, $platform);
        if (!$result[0]) die_error(USER_ERROR, '获取统计资料失败，请重试');
        $models = Model::list_to_array($result['models']);
        echo_result($models);
    }
    //只有指定的管理人员才能补录数据
    if ($action === 21) {
        echo_result(array('is_manager' => $is_manager));
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $platformData = request_object();
    //添加
    if ($action == 1) {
        $db = create_pdo();
        $platform = new p_platform_soft();
        //验证重复数据
        $platform->set_custom_where(" AND ( ww = '" . $platformData->ww . "' OR qq = '" . $platformData->qq . "' OR phone = '" . $platformData->phone . "' ) ");
        $platform->set_query_fields(array('ww', 'qq', 'phone'));
        $result = Model::query_list($db, $platform, $sql);
        if ($result['count'] > 0) die_error(USER_ERROR, "此客户已有记录，添加失败~");
        $platform->reset();
        if (!isset($platformData->customer)) die_error(USER_ERROR, "没有选择售后老师~");

        if (isset($platformData->alipay_account)) $platformData->alipay_account = str_replace(' ', '', $platformData->alipay_account);
        if (isset($platformData->payment_method)) $platformData->payment_method = strs_replace(array(' ', '(', ')', '（', '）'), '', $platformData->payment_method);
        $platform->set_field_from_array($platformData);
        if (($platformData->customer_id) === 0) {
            $platform->set_customer_id(request_login_userid());
            $platform->set_customer(request_login_username());
        }
        $date = $platformData->add_time == '' ? date("Y-m-d H:i:s") : $platformData->add_time;
        $platform->set_add_time($date);

        $result = $platform->insert($db);
        if (!$result[0]) die_error(USER_ERROR, "添加平台业绩信息失败~");
        $action = 3;
        $date = isset($platformData->add_time) ? $platformData->add_time : 0;
        $ind_refund_rate = 1;
        update_customerstatistics($db, $platformData->customer_id, $platformData->money, $action, $date, $ind_refund_rate);
        echo_msg("添加平台业绩信息成功~");
    }
    //删除
    if ($action == 2) {
        $db = create_pdo();
        //获取被删除记录的时间，计算指定售后的售后统计记录
        $platform = new p_platform_soft($platformData->id);
        $result = $platform->load($db, $platform);
        if (!$result[0]) die_error(USER_ERROR, '系统错误,请稍后重试~');
        $platform_res = $platform->to_array();
        $date = $platform_res['add_time'];
        $action = 3;
        $ind_refund_rate = -1;
        update_customerstatistics($db, $platformData->customer_id, -($platformData->money), $action, $date, $ind_refund_rate); //删除了流量业绩，售后统计的出单数也要减一

        $platform = new p_platform_soft($platformData->id);
        $result = $platform->delete($db);
        if (!$result[0]) die_error(USER_ERROR, '删除平台业绩信息失败');
        echo_msg('删除平台业绩信息成功~');
    }
    //修改信息
    if ($action == 3) {
        $db = create_pdo();
        $platform = new p_platform_soft($platformData->id);
        $platform->load($db, $platform);
        $res_old = $platform->to_array();
        $money = ($platformData->money) - $res_old['money'];
        $date = $res_old['add_time'];
        $ind_refund_rate = 1;
        $date_post = $platformData->add_time;
        $action = 3;
        $fuck_array = array(1, 161, 163, 441);
        $login_user_id = request_login_userid();
        if (!in_array($login_user_id, $fuck_array)) {
            if (!is_today($date)) die_error(USER_ERROR, '只能修改当天数据~');
        }
        if($date == $date_post){
            update_customerstatistics($db, $platformData->customer_id, $money, $action, $date);
        }
        //修改了时间
        else{
            update_customerstatistics($db, $platformData->customer_id, -$res_old['money'], $action, $date,-$ind_refund_rate);
            update_customerstatistics($db, $platformData->customer_id, $platformData->money, $action, $date_post,$ind_refund_rate);
        }
        $platform->reset();
        $platform->set_field_from_array($platformData);
        $platform->set_id($platformData->id);
        $result = $platform->update($db, true);
        //如果是添加记录的售后修改影响的行数等于1，表示这次编辑修改了数据
        if($platformData->customer_id == request_login_userid() && $result[1]){
            $platform->set_is_edit(1);
            $result = $platform->update($db, true);
        }      
        if (!$result[0]) die_error(USER_ERROR, '保存统计资料失败');
        echo_msg('保存成功');
    }
});
