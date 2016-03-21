<?php

/**
 * 实物业绩
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/7/13
 */
use Models\Base\Model;
use Models\p_physica;
use Models\Base\SqlOperator;

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
    $keyWord = request_string("keyWord");
    $searchTime = request_string("searchTime");
    $searchStartTime = request_string("searchStartTime");
    $searchEndTime = request_string("searchEndTime");
    $qq = request_string("qq");
    if ($action == 1) {
        $physica = new p_physica();
        if (isset($keyWord)) {
            $physica->set_custom_where(" AND (customer LIKE '%" . $keyWord . "%' OR ww LIKE '%" . $keyWord . "%' OR qq LIKE '%" . $keyWord . "%' OR name LIKE '%" . $keyWord . "%' OR phone LIKE '%" . $keyWord . "%' OR alipay_account LIKE '%" . $keyWord . "%' OR payment_method LIKE '%" . $keyWord . "%' OR tra_num LIKE '%" . $keyWord . "%' OR tra_num LIKE '%" . $keyWord . "%') ");
        }
        if (isset($searchTime)) {
            $searchStartTime = date("Y-m-d 03:00:00", strtotime($searchTime));
            $searchEndTime = date("Y-m-d 03:00:00", strtotime('+1 day', strtotime($searchTime)));
            $physica->set_custom_where(" AND DATE_FORMAT(add_time,'%Y-%m-%d %H:%i:%s') >= '" . $searchStartTime . "' ");
            $physica->set_custom_where(" AND DATE_FORMAT(add_time,'%Y-%m-%d %H:%i:%s') <= '" . $searchEndTime . "' ");
        }
        if (isset($searchStartTime)) {
            $formatStr = '%Y-%m-%d';
            if (strlen($searchStartTime) > 10) {
                $formatStr = "%Y-%m-%d %H:%i";
            }
            $physica->set_custom_where(" and DATE_FORMAT(add_time, '" . $formatStr . "') >= '" . $searchStartTime . "' ");
        }
        if (isset($searchEndTime)) {
            $formatStr = '%Y-%m-%d';
            if (strlen($searchEndTime) > 10) {
                $formatStr = "%Y-%m-%d %H:%i";
            }
            $physica->set_custom_where(" and DATE_FORMAT(add_time, '" . $formatStr . "') <= '" . $searchEndTime . "' ");
        }
        if (isset($sort) && isset($sortname)) {
            $physica->set_order_by($physica->get_field_by_name($sortname), $sort);
        } else {
            $physica->set_order_by(p_physica::$field_add_time, 'DESC');
        }
        $physica->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $physica, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取统计资料失败，请重试');
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($is_manager, $login_userid) {
                    if (!$is_manager && !str_equals($d['rception_id'], $login_userid) && !str_equals($d['customer_id'], $login_userid)) {
                        $d['ww'] = '****';
                        $d['name'] = '***';
                        $d['qq'] = '********';
                        $d['phone'] = '***********';
                        $d['alipay_account'] = '***********';
                        $d['agent_category'] = '****';
                        $d['agent_price'] = '***';
                    }
                    $d['is_manager'] = $is_manager;
                });
        echo_list_result($result, $models, array('current_date' => date('Y-m-d')));
    }

    if ($action == 5) {
        $physica = new p_physica();
        $physica->set_query_fields(array('id', 'qq', 'ww', 'phone', 'payment_method', 'customer', 'customer_id')); //'name', 
        $physica->set_custom_where(" AND ( qq = '" . $qq . "' OR ww = '" . $qq . "' OR phone = '" . $qq . "' ) "); //"' OR name = '" . $qq . 
        $db = create_pdo();
        $physica_result = Model::query_list($db, $physica);
        $physica_list = Model::list_to_array($physica_result['models']);
        echo_result($physica_list);
    }
    if ($action == 10) {
        $startTime = request_string("start_time");
        $endTime = request_string("end_time");
        $expolt = new ExportData2Excel();
        $physica = new p_physica();
        if (isset($startTime)) {
            $startTime = date("Y-m-d 03:00:00", strtotime($startTime));
            $physica->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d %H:%i:%s') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $endTime = date("Y-m-d 03:00:00", strtotime('+1 day', strtotime($endTime)));
            $physica->set_custom_where(" and DATE_FORMAT(add_time, '%Y-%m-%d %H:%i:%s') <= '" . $endTime . "' ");
        }
        $fields = array('add_time', 'ww', 'qq', 'name', 'phone', 'agent_category', 'agent_price', 'all_price', 'free_price', 'free_decoration', 'isArrears', 'isTeaching', 'alipay_account', 'payment_method', 'customer', 'tra_num', 'p_arrears', 'remark');
        $physica->set_query_fields($fields);
        $db = create_pdo();
        $result = Model::query_list($db, $physica, NULL, true);
        if (!$result[0]) {
            $expolt->create(array('导出错误'), array(array('实物业绩数据导出失败,请稍后重试!')), "实物业绩数据导出", "实物业绩");
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $d['isArrears'] = $d['isArrears'] === 0 ? "否" : '是';
                    $d['isTeaching'] = $d['isTeaching'] === 0 ? "否" : '是';
                    $d['tra_num'] = $d['tra_num'] . " ";
                });
        $expolt->set_field($fields);
        $title_array = array('添加时间', '旺旺', 'QQ', '真实姓名', '手机号码', '代理类目', '代理金额', '总金额', '免费装修金额', '免费装修次数', '是否欠款', '是否立即教学', '支付宝账号', '支付方式', '售后名称', '交易号', '欠款', '备注');
        $expolt->create($title_array, $models, "实物业绩数据导出", "实物业绩");
    }
    //只有指定的管理人员才能补录数据
    if ($action == 21) {
        echo_result(array('is_manager' => in_array(request_login_userid(), array('35', '37'))));
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $physicaData = request_object();
    //添加
    if ($action == 1) {
        $physica = new p_physica();
        $db = create_pdo();
        //验证重复数据
        $physica->set_custom_where(" AND ( ww = '" . $physicaData->ww . "' OR qq = '" . $physicaData->qq . "' OR phone = '" . $physicaData->phone . "' ) ");
        $physica->set_query_fields(array('ww', 'qq', 'phone'));
        $result = Model::query_list($db, $physica, NULL, true);
        if ($result['count'] > 0) die_error(USER_ERROR, "此客户已有记录，添加失败~");

        $physica->reset();
        if (!isset($physicaData->customer)) die_error(USER_ERROR, "没有选择售后老师~");
        if (isset($physicaData->alipay_account)) $physicaData->alipay_account = str_replace(' ', '', $physicaData->alipay_account);
        if (isset($physicaData->payment_method)) $physicaData->payment_method = strs_replace(array(' ', '(', ')', '（', '）'), '', $physicaData->payment_method);
        $physica->set_field_from_array($physicaData);
        if ($physicaData->free_decoration) {
            $physica->set_free_decoration(get_num($physicaData->free_decoration));
            if (isset($physicaData->all_price)) {
                $agent_price = round(($physicaData->all_price) * 0.8, 2);
                $physica->set_agent_price($agent_price);
                $free_price = round(($physicaData->all_price) * 0.2, 2);
                $physica->set_free_price($free_price);
            }
        } else {
            if (isset($physicaData->all_price)) {
                $physica->set_agent_price($physicaData->all_price);
                $physica->set_all_price(0);
            }
        }
        if (($physicaData->customer_id) === 0) {
            $physica->set_customer_id(request_login_userid());
            $physica->set_customer(request_login_username());
        }
        $date = $physicaData->add_time == '' ? date("Y-m-d H:i:s") : $physicaData->add_time;

        $physica->set_add_time($date);
        $result = $physica->insert($db);
        if (!$result[0]) die_error(USER_ERROR, "添加实物业绩信息失败~");
        //    update_customerstatistics($db, $physicaData->customer_id, $physicaData->all_price, 4);
      
        //添加后向创想范人员发送审核消息
        $text = "亲，您有一笔二销业绩需要审核，请及时处理！";
        $msg = array('title' => "二销业绩审核消息", 'addtime' => date("Y-m-d H:i:s"), 'text' => $text, 'Code' => 0, 'Msg' => '', 'MsgType' => 5);
        send_push_msg(json_encode($msg), 435);  
        
        echo_msg("添加实物业绩信息成功~");
    }
    //删除
    if ($action == 2) {
        $db = create_pdo();
        $physica = new p_physica($physicaData->id);
        $result = $physica->load($db, $physica);
        if (!$result[0]) die_error(USER_ERROR, '系统错误,请稍后重试~');
        $res_old = $physica->to_array();
        $date = $res_old['add_time'];
        $date_post = $physicaData->add_time;
        $isTe = $res_old['isTe'];
        if ($isTe) {
            $action = 4;
            update_customerstatistics($db, $physicaData->customer_id, -($physicaData->agent_price), $action, $date);
        }
        //只有审核过的才进入售后统计
        if ($physicaData->isTe || $isTe) {            
            $action = 4;
            if($date == $date_post){
                update_customerstatistics($db, $physicaData->customer_id, $money, $action, $date);
            }
            else{
                update_customerstatistics($db, $physicaData->customer_id, -$res_old['agent_price'], $action, $date);
                update_customerstatistics($db, $physicaData->customer_id, $physicaData->all_price, $action, $date_post);
            }
        }
        $physica = new p_physica($physicaData->id);
        $result = $physica->delete($db);
        if (!$result[0]) die_error(USER_ERROR, '删除实物业绩信息失败');
        echo_msg('删除实物业绩信息成功~');
    }
    //修改信息
    if ($action == 3) {
        $db = create_pdo();
        $physica = new p_physica($physicaData->id);
        $physica->load($db, $physica);
        $res_old = $physica->to_array();
        $money = !$physicaData->isTe ? (($physicaData->all_price) - $res_old['agent_price']) : $res_old['agent_price'];
        $date = $res_old['add_time'];
        $date_post = $physicaData->add_time;
        $isTe = $res_old['isTe'];

        $fuck_array = array(1, 35, 37);
        $login_user_id = request_login_userid();
        if (!$physicaData->isTe && !in_array($login_user_id, $fuck_array)) {
            if (!is_today($date)) die_error(USER_ERROR, '只能修改当天数据~');
        }
        //只有审核过的才进入售后统计
        if ($physicaData->isTe || $isTe) {            
            $action = 4;
            if($date == $date_post){
                update_customerstatistics($db, $physicaData->customer_id, $money, $action, $date);
            }
            else{
                update_customerstatistics($db, $physicaData->customer_id, -$res_old['agent_price'], $action, $date);
                update_customerstatistics($db, $physicaData->customer_id, $physicaData->all_price, $action, $date_post);
            }
        }
        $physica->reset();
        $physica->set_field_from_array($physicaData);
        $physica->set_id($physicaData->id);
        //事实上下面这个if ($physicaData->free_decoration)是废弃的代码，是以前的业务逻辑，只是可能担心后面业务部门又会使用，故没有删除，目前这段代码没有执行。只是设计欠账越多以后维护成本越高。
        if ($physicaData->free_decoration) {
            $physica->set_free_decoration(get_num($physicaData->free_decoration));
            if (isset($physicaData->all_price)) {
                $agent_price = round(($physicaData->all_price) * 0.8, 2);
                $physica->set_agent_price($agent_price);
                $free_price = round(($physicaData->all_price) * 0.2, 2);
                $physica->set_free_price($free_price);
            }
        } else {
            if (isset($physicaData->all_price)) {
                $physica->set_agent_price($physicaData->all_price);
            }
            if (isset($physicaData->agent_price)) {
                $physica->set_agent_price($physicaData->agent_price);
            }
            $physica->set_all_price(0);
            $physica->set_free_price(0);
        }
        if ($physicaData->free_decoration) {
            $physica->set_free_decoration(get_num($physicaData->free_decoration));
        }
        $result = $physica->update($db, true);
        //如果是添加记录的售后修改影响的行数等于1，表示这次编辑修改了数据
        if($physicaData->customer_id == request_login_userid() && $result[1]){
            $physica->set_is_edit(1);
            $result = $physica->update($db, true);
        }
        if (!$result[0]) die_error(USER_ERROR, '保存统计资料失败');
        echo_msg('保存成功');
    }
});

function get_num($str) {
    $str_array = array(
        '一次' => 1,
        '两次' => 2,
        '三次' => 3,
        '四次' => 4,
        '五次' => 5
    );
    return $str_array[$str];
}

/**
 * 售后,,当前登陆
 * 
 * 
 * 
 */