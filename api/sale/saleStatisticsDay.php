<?php

/**
 * 售前统计
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/5/07
 */
use Models\Base\Model;
use Models\M_User;
use Models\p_salestatistics;
use Models\Base\SqlOperator;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $h = (int) date("H");
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');
        $searchTime = request_string('searchTime');
        $searchChannel = request_string("searchChannel");
        $userid = request_userid();
        $manager_role_ids = array(0, 102, 408, 701, 702, 703, 704, 709, 710, 1103);
        $manager_dept_ids = array(4);
        $employee = get_employees()[$userid];
        $role_id = $employee['role_id'];
        $dept_id = $employee['dept1_id'];
        $is_role_manager = in_array($role_id, $manager_role_ids);
        $is_dept_manager = in_array($dept_id, $manager_dept_ids);
        $is_manager = $is_role_manager || $is_dept_manager;
        if (!isset($searchTime)) {
            $searchTime = date("Y-m-d");
            if ($h < 3) {
                $searchTime = date('Y-m-d', strtotime("-1 day"));
            }
        }
        $saleStatistics = new P_SaleStatistics();
        if (isset($searchName)) {
            $saleStatistics->set_where_and(p_salestatistics::$field_username, SqlOperator::Like, '%' . $searchName . '%');
        }
        if (isset($searchTime)) {
            $saleStatistics->set_custom_where(" AND DATE_FORMAT(reltime,'%Y-%m-%d') = '" . $searchTime . "' ");
        }
        if (!$is_manager) {
            $saleStatistics->set_where_and(p_salestatistics::$field_userid, SqlOperator::Equals, request_login_userid());
        }
        if (isset($searchChannel)) {
            $saleStatistics->set_where_and(p_salestatistics::$field_channel, SqlOperator::Equals, $searchChannel);
        }
        if (isset($sort) && isset($sortname)) {
            $saleStatistics->set_order_by($saleStatistics->get_field_by_name($sortname), $sort);
        } else {
            $saleStatistics->set_order_by(p_salestatistics::$field_reltime, 'desc');
        }
        $saleStatistics->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $saleStatistics, null, true);
        if (!$result[0]) {
            die_error(USER_ERROR, '获取统计资料失败，请重试');
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($is_manager, $userid, $searchTime) {
                    $loss_number = (int) $d['into_count'] - (int) $d['accept_count'];
                    $d['loss_number'] = $loss_number; //流失数/日
                    $d['loss_rate'] = sprintf("%.2f", ($loss_number / $d['into_count']) * 100) . "%"; //流失率/日 %
                    $d['timely_rate'] = sprintf("%.2f", ($d['timely_count'] / $d['accept_count']) * 100) . "%"; //及时率/日 %
                    $d['timely_turnover_ratio'] = sprintf("%.2f", ($d['timely_count'] / $d['deal_count']) * 100) . "%"; //及时成交占比 %
                    $d['conversion_rate'] = sprintf("%.2f", ($d['deal_count'] / $d['into_count']) * 100) . "%"; //转化率/日 %
                    $d['average_price'] = sprintf("%.2f", ($d['amount'] / $d['deal_count'])); //均价/日
                    if ($is_manager) {
                        $d['edit'] = true;
                        $d['dele'] = true;
                    } else {
                        $d['edit'] = ($userid == $d['userid']) && str_equals(date('Y-m-d', strtotime($d['reltime'])), $searchTime);
                        $d['dele'] = false;
                    }
                });
        array_sort_by_field($models, 'conversion_rate', SORT_DESC);
        $d = array('into_count' => 0, 'accept_count' => 0, 'deal_count' => 0, 'timely_count' => 0, 'amount' => 0.00, 'commission' => '/', 'loss_number' => 0, 'loss_rate' => 0, 'timely_rate' => 0, 'timely_turnover_ratio' => 0, 'conversion_rate' => 0, 'average_price' => 0, 'reltime' => $searchTime);
        if ($is_manager) {
            $month_count_sql = "SELECT IFNULL(SUM(ps.into_count),0) AS into_count,IFNULL(SUM(ps.accept_count),0) AS accept_count,IFNULL(SUM(ps.deal_count),0) AS deal_count,IFNULL(SUM(ps.commission),0) AS commission,";
            $month_count_sql.="IFNULL(SUM(ps.timely_count),0) AS timely_count,IFNULL(SUM(ps.amount),0) AS amount,'" . $searchTime . "' AS reltime from P_SaleStatistics ps WHERE 1=1 ";
            $month_count_sql.=" AND DATE_FORMAT(ps.reltime,'%Y-%m-%d') = '" . $searchTime . "' ";
            if (isset($searchChannel)) {
                $month_count_sql.=" AND ps.channel ='" . $searchChannel . "' ";
            }
            $month_count_result = Model::execute_custom_sql($db, $month_count_sql);
            if (!$month_count_result[0]) {
                die_error(USER_ERROR, '获取统计资料失败，请重试');
            }
            $d = $month_count_result['results'][0];
            $loss_number = (int) $d['into_count'] - (int) $d['accept_count'];
            $d['loss_number'] = $loss_number; //流失数/日
            $d['loss_rate'] = sprintf("%.2f", ($loss_number / $d['into_count']) * 100) . "%"; //流失率/日 %
            $d['timely_rate'] = sprintf("%.2f", ($d['timely_count'] / $d['accept_count']) * 100) . "%"; //及时率/日 %
            $d['timely_turnover_ratio'] = sprintf("%.2f", ($d['timely_count'] / $d['deal_count']) * 100) . "%"; //及时成交占比 %
            $d['conversion_rate'] = sprintf("%.2f", ($d['deal_count'] / $d['into_count']) * 100) . "%"; //转化率/日 %
            $d['average_price'] = sprintf("%.2f", ($d['amount'] / $d['deal_count'])); //均价/日
        }
        echo_list_result($result, $models, array('is_manager' => $is_manager, 'total_month_count' => $d));
    }
    /**
     * 获取销售部门提成等级
     */
    if ($action == 2) {
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchUserName = request_string('searchUserName');
        $employee = new M_User();
        $employee->set_where_and(M_User::$field_dept1_id, SqlOperator::Equals, 7);
        $employee->set_where_and(M_User::$field_status, SqlOperator::In, array(1, 2));
        if (isset($searchUserName)) {
            $employee->set_custom_where(" AND username like '%" . $searchUserName . "%' ");
        }
        $employee->set_query_fields(array('userid', 'username', 'salecount_lv'));
        if (isset($sort) && isset($sortname)) {
            $employee->set_order_by($employee->get_field_by_name($sortname), $sort);
        } else {
            $employee->set_order_by(M_User::$field_userid, 'ASC');
        }
        $employee->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $employee, NULL, true);
        if (!$result[0]) {
            die_error(USER_ERROR, '获取统计资料失败，请重试');
        }
        $models = Model::list_to_array($result['models']);
        echo_list_result($result, $models);
    }
    if ($action == 11) {
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $channel = request_string("channel");
        $export = new ExportData2Excel();
        $saleStatistics = new P_SaleStatistics();
        if (isset($startTime)) {
            $saleStatistics->set_custom_where(" AND DATE_FORMAT(reltime, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $saleStatistics->set_custom_where(" AND DATE_FORMAT(reltime, '%Y-%m-%d') <= '" . $endTime . "' ");
        }
        if (isset($channel)) {
            $saleStatistics->set_where_and(p_salestatistics::$field_channel, SqlOperator::Equals, $channel);
        }
        $saleStatistics->set_order_by(p_salestatistics::$field_reltime, 'desc');
        $db = create_pdo();
        $result = Model::query_list($db, $saleStatistics);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('售前统计(日)数据导出失败,请稍后重试!')), "售前统计(日)数据导出", "售前统计(日)");
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $loss_number = (int) $d['into_count'] - (int) $d['accept_count'];
                    $d['loss_number'] = $loss_number; //流失数/日
                    $d['loss_rate'] = sprintf("%.2f", ($loss_number / $d['into_count']) * 100) . "%"; //流失率/日 %
                    $d['timely_rate'] = sprintf("%.2f", ($d['timely_count'] / $d['accept_count']) * 100) . "%"; //及时率/日 %
                    $d['timely_turnover_ratio'] = sprintf("%.2f", ($d['timely_count'] / $d['deal_count']) * 100) . "%"; //及时成交占比 %
                    $d['conversion_rate'] = sprintf("%.2f", ($d['deal_count'] / $d['into_count']) * 100) . "%"; //转化率/日 %
                    $d['average_price'] = sprintf("%.2f", ($d['amount'] / $d['deal_count'])); //均价/日
                });
        $title_array = array('姓名', '提成等级', '渠道', '转入数/日', '接入数/日', '成交数/日', '及时数/日', '金额/日', '提成/日', '流失数/日', '流失率/日', '及时率/日', '及时成交占比', '转化率/日', '均价/日', '日期');
        $field = array('username', 'salecount_lv', 'channel', 'into_count', 'accept_count', 'deal_count', 'timely_count', 'amount', 'commission', 'loss_number', 'loss_rate', 'timely_rate', 'timely_turnover_ratio', 'conversion_rate', 'average_price', 'reltime');
        $export->set_field($field);
        $export->set_field_width(array(12, 8, 15, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 17));
        $export->create($title_array, $models, "售前统计(日)数据导出", "售前统计(日)");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $saleStatisticsData = request_object();
    if ($action == 1) {
        $reltime = date("Y-m-d");
        $h = (int) date("H");
        if ($h < 3) {
            $reltime = date('Y-m-d', strtotime("-1 day"));
        }
        $saleStatistics = new p_salestatistics();
        $saleStatistics->set_custom_where(" AND userid = " . request_login_userid() . " AND DATE_FORMAT(reltime,'%Y-%m-%d') ='" . $reltime . "' ");
        $db = create_pdo();
        $count = $saleStatistics->count($db);
        if (isset($count) && $count > 0) die_error(USER_ERROR, '添加统计失败，当天统计资料已录入~');
        $saleStatistics->reset();
        $saleStatistics->set_field_from_array($saleStatisticsData);
        $saleStatistics->set_addtime('now');
        $saleStatistics->set_reltime($reltime);
        $user = new M_User();
        $user->set_query_fields(array('salecount_lv'));
        $user->set_where_and(M_User::$field_userid, SqlOperator::Equals, request_login_userid());
        $result_user = $user->load($db, $user);
        if (!$result_user[0]) die_error(USER_ERROR, '添加统计失败~');
        $amount = (int) $saleStatisticsData->amount;
        $commission = get_commission($amount, $user->get_salecount_lv());
        $saleStatistics->set_commission($commission);
        $saleStatistics->set_salecount_lv($user->get_salecount_lv());
        $employee = get_employees()[$saleStatisticsData->userid];
        $result = $saleStatistics->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加统计失败~');
        echo_msg('添加成功');
    }
    if ($action == 2) {
        $saleStatistics = new P_SaleStatistics($saleStatisticsData->id);
        $db = create_pdo();
        $result = $saleStatistics->delete($db);
        if (!$result[0]) die_error(USER_ERROR, '删除失败。');
        echo_msg('删除成功');
    }
    if ($action == 3) {
        $saleStatistics = new P_SaleStatistics($saleStatisticsData->id);
        //有修改权限的用户ID：63:无崖子  99:鬼谷子  16:知夏  189:夏娜
        $manager_userids = array(63, 99, 16, 189);
        //不允许修改超过次日凌晨3点的数据，即超过27小时的数据
        if (!in_array(request_login_userid(), $manager_userids) && time_diff_unit($saleStatisticsData->addtime, time(), 'h') >= 27) die_error(USER_ERROR, '已经超过27小时的数据不能再修改~');
        $saleStatistics->set_field_from_array($saleStatisticsData);
        $user = new M_User();
        $user->set_query_fields(array('salecount_lv'));
        $user->set_where_and(M_User::$field_userid, SqlOperator::Equals, $saleStatisticsData->userid);
        $db = create_pdo();
        $result_user = $user->load($db, $user);
        if (!$result_user[0]) die_error(USER_ERROR, '获取用户信息失败~');
        $amount = (int) $saleStatisticsData->amount;
        $commission = get_commission($amount, $user->get_salecount_lv());
        $saleStatistics->set_commission($commission);
        add_data_change_log($db, $saleStatisticsData, $saleStatistics, 13);
        $result = $saleStatistics->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '修改失败。');
        echo_msg('修改成功！');
    }
    /**
     * 修改销售等级
     */
    if ($action == 4) {
        $employee = new M_User($saleStatisticsData->userid);
        $employee->set_salecount_lv($saleStatisticsData->salecount_lv);
        $db = create_pdo();
        $result = $employee->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '修改失败。');
        echo_msg('修改成功');
    }
});
