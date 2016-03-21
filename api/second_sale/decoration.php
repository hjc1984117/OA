<?php

/**
 * 装修业绩
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/7/13
 */
use Models\Base\Model;
use Models\P_Decoration;
use Models\Base\SqlOperator;
use Models\p_customerstatistics;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../common/http.php';
require '../../api/sale/update_customerstatistics.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {

    $login_userid = request_login_userid();
    $manager_role_ids = array(0, 101, 102, 103, 401, 402, 403, 404, 408, 601, 602, 701, 702, 713, 714, 715, 801, 802, 901, 902, 1102, 1103);
    $manager_userids = array(43, 187, 39, 48, 42, 52, 291, 19, 24, 25, 26, 27, 30, 31, 32, 263, 316, 16, 435, 441);
    $is_manager = in_array(get_role_id(), $manager_role_ids) || in_array($login_userid, $manager_userids);

    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $keyWord = request_string("workName");
    $searchTime = request_string("searchTime");
    $searchStartTime = request_string("searchStartTime");
    $searchEndTime = request_string("searchEndTime");
    $qq = request_string("qq");
    if ($action == 1) {
        $decoration = new P_Decoration();
        if (isset($keyWord)) {
            $decoration->set_custom_where(" AND (customer LIKE '%" . $keyWord . "%' OR ww LIKE '%" . $keyWord . "%' OR qq LIKE '%" . $keyWord . "%' OR name LIKE '%" . $keyWord . "%' OR phone LIKE '%" . $keyWord . "%' OR decoration_packages LIKE '%" . $keyWord . "%' OR decoration_price LIKE '%" . $keyWord . "%' OR payment_method LIKE '%" . $keyWord . "%' OR tra_num LIKE '%" . $keyWord . "%' OR tra_num LIKE '%" . $keyWord . "%') ");
        }
        if (isset($searchTime)) {
            $searchStartTime = date("Y-m-d 03:00:00", strtotime($searchTime));
            $searchEndTime = date("Y-m-d 03:00:00", strtotime('+1 day', strtotime($searchTime)));
            $decoration->set_custom_where(" AND DATE_FORMAT(add_time,'%Y-%m-%d %H:%i:%s') >= '" . $searchStartTime . "' ");
            $decoration->set_custom_where(" AND DATE_FORMAT(add_time,'%Y-%m-%d %H:%i:%s') <= '" . $searchEndTime . "' ");
        }
        if (isset($searchStartTime)) {
            $formatStr = '%Y-%m-%d';
            if (strlen($searchStartTime) > 10) {
                $formatStr = "%Y-%m-%d %H:%i";
            }
            $decoration->set_custom_where(" and DATE_FORMAT(add_time, '" . $formatStr . "') >= '" . $searchStartTime . "' ");
        }
        if (isset($searchEndTime)) {
            $formatStr = '%Y-%m-%d';
            if (strlen($searchEndTime) > 10) {
                $formatStr = "%Y-%m-%d %H:%i";
            }
            $decoration->set_custom_where(" and DATE_FORMAT(add_time, '" . $formatStr . "') <= '" . $searchEndTime . "' ");
        }
        if (isset($sort) && isset($sortname)) {
            $decoration->set_order_by($decoration->get_field_by_name($sortname), $sort);
        } else {
            $decoration->set_order_by(P_Decoration::$field_add_time, 'DESC');
        }
        $decoration->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $decoration, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取统计资料失败，请重试');
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($is_manager, $login_userid) {
                    if (!$is_manager && !str_equals($d['rception_id'], $login_userid) && !str_equals($d['customer_id'], $login_userid)) {
                        $d['ww'] = '****';
                        $d['name'] = '***';
                        $d['qq'] = '********';
                        $d['phone'] = '***********';
                        $d['alipay_account'] = '***********';
                        $d['decoration_packages'] = '***';
                        $d['decoration_price'] = '***';
                    }
                    $d['is_manager'] = $is_manager;
                });
        echo_list_result($result, $models, array('current_date' => date('Y-m-d')));
    }
    if ($action == 5) {
        $decoration = new P_Decoration();
        $decoration->set_query_fields(array('id', 'qq', 'ww', 'phone', 'payment_method', 'customer', 'customer_id')); // 'name',
        $decoration->set_custom_where(" AND ( qq = '" . $qq . "' OR ww = '" . $qq . "' OR phone = '" . $qq . "' ) "); //"' OR name = '" . $qq . 
        $db = create_pdo();
        $decoration_result = Model::query_list($db, $decoration);
        $decoration_list = Model::list_to_array($decoration_result['models']);
        echo_result($decoration_list);
    }
    if ($action == 10) {
        $startTime = request_string("start_time");
        $endTime = request_string("end_time");
        $expolt = new ExportData2Excel();
        $decoration = new P_Decoration();
        if (isset($startTime)) {
            $startTime = date("Y-m-d 03:00:00", strtotime($startTime));
            $decoration->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d %H:%i:%s') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $endTime = date("Y-m-d 03:00:00", strtotime('+1 day', strtotime($endTime)));
            $decoration->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d %H:%i:%s') <= '" . $endTime . "' ");
        }
        $decoration->set_query_fields(array('add_time', 'ww', 'qq', 'name', 'phone', 'decoration_packages', 'decoration_price', 'isArrears', 'alipay_account', 'payment_method', 'customer', 'rception'));
        $db = create_pdo();
        $result = Model::query_list($db, $decoration, NULL, true);
        if (!$result[0]) {
            $expolt->create(array('导出错误'), array(array('装修业绩数据导出失败,请稍后重试!')), "平台业绩数据导出", "平台业绩");
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $d['isArrears'] = $d['isArrears'] === 0 ? "否" : '是';
                });
        $title_array = array('添加时间', '旺旺', 'QQ', '真实姓名', '手机号码', '装修套餐', '装修金额', '是否欠款', '支付宝账号', '支付方式', '售后名称', '接待人员');
        $expolt->create($title_array, $models, "装修业绩数据导出", "装修业绩");
    }
    //只有指定的管理人员才能补录数据
    if ($action == 21) {
        echo_result(array('is_manager' => in_array(request_login_userid(), array('35', '37'))));
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $decorationData = request_object();
    //添加
    if ($action == 1) {
        $decoration = new P_Decoration();
        $db = create_pdo();
        //重复数据验证
        $decoration->set_custom_where(" AND ( ww = '" . $decorationData->ww . "' OR qq = '" . $decorationData->qq . "' OR phone = '" . $decorationData->phone . "' ) ");
        $decoration->set_query_fields(array('ww', 'qq', 'phone'));
        $result = Model::query_list($db, $decoration, NULL, true);
        if ($result['count'] > 0) die_error(USER_ERROR, "此客户已有记录，添加失败~");

        $decoration->reset();
        if (!isset($decorationData->customer)) die_error(USER_ERROR, "没有选择售后老师~");
        if (isset($decorationData->alipay_account)) $decorationData->alipay_account = str_replace(' ', '', $decorationData->alipay_account);
        if (isset($decorationData->payment_method)) $decorationData->payment_method = strs_replace(array(' ', '(', ')', '（', '）'), '', $decorationData->payment_method);
        $decoration->set_field_from_array($decorationData);
        if (($decorationData->customer_id) === 0) {
            $decoration->set_customer_id(request_login_userid());
            $decoration->set_customer(request_login_username());
        }
        $date = $decorationData->add_time == '' ? date("Y-m-d H:i:s") : $decorationData->add_time;
        $decoration->set_add_time($date);
        $result = $decoration->insert($db);
        if (!$result[0]) die_error(USER_ERROR, "添加装修业绩信息失败~");
        //   update_customerstatistics($db, $decorationData->customer_id, $decorationData->decoration_price, 5);
       
       //添加后向创想范人员发送审核消息
        $text = "亲，您有一笔二销业绩需要审核，请及时处理！";
        $msg = array('title' => "二销业绩审核消息", 'addtime' => date("Y-m-d H:i:s"), 'text' => $text, 'Code' => 0, 'Msg' => '', 'MsgType' => 5);
        send_push_msg(json_encode($msg), 435);       
        echo_msg("添加装修业绩信息成功~");
    }
    //删除
    if ($action == 2) {
        $db = create_pdo();
        //获取被删除记录的时间，计算指定售后的售后统计记录
        $decoration = new P_Decoration($decorationData->id);
        $result = $decoration->load($db, $decoration);
        if (!$result[0]) die_error(USER_ERROR, '系统错误,请稍后重试~');
        $decoration_res = $decoration->to_array();
        $date = $decoration_res['add_time'];
        $isTe = $decoration_res['isTe'];
        if ($isTe) {
            $action = 5;
            update_customerstatistics($db, $decorationData->customer_id, -($decorationData->decoration_price), $action, $date);
        }

        $decoration = new P_Decoration($decorationData->id);
        $result = $decoration->delete($db);
        if (!$result[0]) die_error(USER_ERROR, '删除装修业绩信息失败');

        echo_msg('删除装修业绩信息成功~');
    }
    //修改信息
    if ($action == 3) {
        $db = create_pdo();
        $fuck_array = array(1, 35, 37);
        $login_user_id = request_login_userid();
        $decoration = new P_Decoration($decorationData->id);
        $decoration->load($db, $decoration);
        $res_old = $decoration->to_array();
        $decoration_price = !$decorationData->isTe ? (($decorationData->decoration_price) - $res_old['decoration_price']) : $res_old['decoration_price'];
        $date = $res_old['add_time'];
        $date_post = $decorationData->add_time;
        $isTe = $res_old['isTe'];
        if (!$decorationData->isTe && !in_array($login_user_id, $fuck_array)) {
            if (!is_today($date)) die_error(USER_ERROR, '只能修改当天数据~');
        }
        if ($decorationData->isTe || $isTe) {
              $action = 5;
             if($date == $date_post){
                 update_customerstatistics($db, $decorationData->customer_id, $decoration_price, $action, $date);
             }
             //修改了时间
             else{
                 update_customerstatistics($db, $decorationData->customer_id, -$res_old['decoration_price'], $action, $date);
                 update_customerstatistics($db, $decorationData->customer_id, $decorationData->decoration_price, $action, $date_post);
             }            
        }

        $decoration = new P_Decoration($decorationData->id);
        $decoration->set_field_from_array($decorationData);
        $decoration->set_id($decorationData->id);
        $result = $decoration->update($db, true);
        //如果是添加记录的售后修改影响的行数等于1，表示这次编辑修改了数据
        if($decorationData->customer_id == request_login_userid() && $result[1]){
            $decoration->set_is_edit(1);
            $result = $decoration->update($db, true);
        }
        if (!$result[0]) die_error(USER_ERROR, '保存统计资料失败');
        echo_msg('保存成功');
    }
});
