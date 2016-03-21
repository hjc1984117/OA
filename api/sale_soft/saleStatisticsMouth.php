<?php

/**
 * 售前统计
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/5/07
 */
use Models\Base\Model;
use Models\p_salestatistics_soft;
use Models\Base\SqlOperator;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');
        $searchTime = request_string('searchTime');
        $searchChannel = request_string("searchChannel");
        $userid = request_userid();
        $manager_role_ids = array(0, 1101, 1103);
        $manager_dept_ids = array(4);
        $employee = get_employees()[$userid];
        $role_id = $employee['role_id'];
        $dept_id = $employee['dept1_id'];
        $is_role_manager = in_array($role_id, $manager_role_ids);
        $is_dept_manager = in_array($dept_id, $manager_dept_ids);
        $is_manager = $is_role_manager || $is_dept_manager;
        if (!isset($searchTime)) {
            $searchTime = date('Y-m');
        }
        $saleStatistics = new p_salestatistics_soft();
        $sql = "SELECT s.userid,s.username,s.channel,s.salecount_lv,s.elderly_deal_count,SUM(s.commission) AS commission,SUM(s.into_count) AS into_count,SUM(s.accept_count) AS accept_count,SUM(s.deal_count) AS deal_count,";
        $sql.="SUM(s.timely_count) AS timely_count,SUM(s.amount) AS amount,DATE_FORMAT(s.reltime,'%Y-%m') AS reltime FROM p_salestatistics_soft s WHERE 1=1 ";
        if (isset($searchName)) {
            $sql .= "AND s.username like '%" . $searchName . "%' ";
        }
        if (isset($searchChannel)) {
            $sql.=" AND s.channel = '" . $searchChannel . "' ";
        }
        $sql .= "AND DATE_FORMAT(s.reltime, '%Y-%m') = '" . $searchTime . "' GROUP BY s.userid ";
        if (isset($sort) && isset($sortname)) {
            $sql .= "ORDER BY SUM(s." . $sortname . ") " . $sort . " ";
        } else {
            $sql .= "ORDER BY s.addtime desc ";
        }
        $db = create_pdo();
        $count_result = Model::execute_custom_sql($db, $sql);
        if (!$count_result[0]) die_error(USER_ERROR, "获取统计资料失败，请重试");
        $total_count = $count_result['count'];
        $max_page_no = ceil($total_count / request_pagesize());
        $sql .= "LIMIT " . request_pagesize() * (request_pageno() - 1) . "," . request_pagesize();
        $result = Model::query_list($db, $saleStatistics, $sql, true);
        if (!$result[0]) {
            die_error(USER_ERROR, '获取统计资料失败，请重试');
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $loss_number = (int) $d['into_count'] - (int) $d['accept_count'];
                    $d['loss_number'] = $loss_number; //流失数/日
                    $d['loss_rate'] = sprintf("%.2f", ($loss_number / $d['into_count']) * 100); //流失率/日 %
                    $d['timely_rate'] = sprintf("%.2f", ($d['timely_count'] / $d['accept_count']) * 100); //及时率/日 %
                    $d['timely_turnover_ratio'] = sprintf("%.2f", ($d['timely_count'] / $d['deal_count']) * 100); //及时成交占比 %
                    $d['conversion_rate'] = sprintf("%.2f", ($d['deal_count'] / $d['accept_count']) * 100); //转化率/日 %
                    $d['average_price'] = sprintf("%.2f", ($d['amount'] / $d['deal_count'])); //均价/日
                });
        array_sort_by_field($models, 'conversion_rate', SORT_DESC);
        $d = array('into_count' => 0, 'accept_count' => 0, 'deal_count' => 0, 'timely_count' => 0, 'elderly_deal_count' => 0, 'amount' => 0.00, 'commission' => '/', 'loss_number' => 0, 'loss_rate' => 0, 'timely_rate' => 0, 'timely_turnover_ratio' => 0, 'conversion_rate' => 0, 'average_price' => 0, 'reltime' => (isset($searchTime) ? $searchTime : adjust_time('Y-m-d', '03:00:00')));
        if ($is_manager) {
            $month_count_sql = "SELECT ps.salecount_lv, IFNULL(SUM(ps.elderly_deal_count),0) AS elderly_deal_count,IFNULL(SUM(ps.into_count),0) AS into_count,IFNULL(SUM(ps.elderly_deal_count),0) AS elderly_deal_count,IFNULL(SUM(ps.accept_count),0) AS accept_count,IFNULL(SUM(ps.commission),0) AS commission,IFNULL(SUM(ps.deal_count),0) AS deal_count,IFNULL(SUM(ps.timely_count),0) AS timely_count,IFNULL(SUM(ps.amount),0) AS amount,IFNULL(DATE_FORMAT(ps.reltime,'%Y-%m'),'" . $searchTime . "') AS reltime from p_salestatistics_soft ps ";
            $month_count_sql .="WHERE 1=1 AND DATE_FORMAT(ps.reltime, '%Y-%m') = '" . $searchTime . "' ";
            if (isset($searchChannel)) {
                $month_count_sql.=" AND ps.channel = '" . $searchChannel . "' ";
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
            $d['conversion_rate'] = sprintf("%.2f", ($d['deal_count'] / $d['accept_count']) * 100) . "%"; //转化率/日 %
            $d['average_price'] = sprintf("%.2f", ($d['amount'] / $d['deal_count'])); //均价/日
        }
        echo_result(array('code' => 0, 'list' => $models, 'is_manager' => $is_manager, 'total_count' => $total_count, 'page_no' => request_pageno(), 'max_page_no' => $max_page_no, 'total_month_count' => $d, 'start_year' => START_YEAR, 'current_year' => date('Y')));
    }
    if ($action == 11) {
        $startTime = request_string("start_time");
        $endTime = request_string("end_time");
        $channel = request_string("channel");
        $export = new ExportData2Excel(); //
        $sql = "SELECT s.userid,s.username,s.channel,s.salecount_lv,SUM(s.elderly_deal_count) AS elderly_deal_count,SUM(s.commission) AS commission,SUM(s.into_count) AS into_count,SUM(s.accept_count) AS accept_count,SUM(s.deal_count) AS deal_count,";
        $sql.="SUM(s.timely_count) AS timely_count,SUM(s.amount) AS amount,DATE_FORMAT(s.reltime,'%Y-%m') AS reltime FROM p_salestatistics_soft s WHERE 1=1 ";
        if (isset($startTime)) {
            $sql .= "AND DATE_FORMAT(s.reltime, '%Y-%m') >= '" . $startTime . "' ";
        }
        if (isset($endTime)) {
            $sql.="AND DATE_FORMAT(s.reltime, '%Y-%m') <= '" . $endTime . "' ";
        }
        if (isset($channel)) {
            $sql .="AND s.channel = '" . $channel . "' ";
        }
        $sql.="GROUP BY s.userid ORDER BY s.channel DESC";
        $db = create_pdo();
        $result = Model::execute_custom_sql($db, $sql);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('售前统计(月)数据导出失败,请稍后重试!')), "售前统计(月)数据导出", "售前统计(月)");
        }
        $models = $result['results'];
        array_walk($models, function(&$d) {
            $loss_number = (int) $d['into_count'] - (int) $d['accept_count'];
            $d['loss_number'] = $loss_number; //流失数/日
            $d['loss_rate'] = sprintf("%.2f", ($loss_number / $d['into_count']) * 100) . "%"; //流失率/日 %
            $d['timely_rate'] = sprintf("%.2f", ($d['timely_count'] / $d['accept_count']) * 100) . "%"; //及时率/日 %
            $d['timely_turnover_ratio'] = sprintf("%.2f", ($d['timely_count'] / $d['deal_count']) * 100) . "%"; //及时成交占比 %
            $d['conversion_rate'] = sprintf("%.2f", ($d['deal_count'] / $d['accept_count']) * 100) . "%"; //转化率/日 %
            $d['average_price'] = sprintf("%.2f", ($d['amount'] / $d['deal_count'])); //均价/日
        });
        $title_array = array('姓名', '渠道', '转入数/月', '接入数/月', '成交数/月', '老人成交数/月', '及时数/月', '金额/月', '提成/月', '流失数/月', '流失率/月', '及时率/月', '及时成交占比', '转化率/月', '均价/月', '日期');
        $field = array('username', 'channel', 'into_count', 'accept_count', 'deal_count', 'elderly_deal_count', 'timely_count', 'amount', 'commission', 'loss_number', 'loss_rate', 'timely_rate', 'timely_turnover_ratio', 'conversion_rate', 'average_price', 'reltime');
        $export->set_field($field);
        $export->set_field_width(array(12, 15, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 12, 17));
        $export->create($title_array, $models, "售前统计(月)数据导出", "售前统计(月)");
    }
});
