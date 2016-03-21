<?php

/*
 * 考勤配置
 */

use Models\Base\Model;
use Models\Base\SqlSortType;
use Models\w_classes;
use Models\Base\SqlOperator;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../common/http.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $cid = request_string('cid');
        $stime = request_int('stime');
        $etime = request_string('etime');
        $classes = new w_classes();
        if (isset($cid)) {
            $classes->set_where_and(w_classes::$field_cid, SqlOperator::Equals, $cid);
        }
        if (isset($stime)) {
            $classes->set_where_and(w_classes::$field_stime, SqlOperator::Equals, $stime);
        }
        if (isset($etime)) {
            $classes->set_where_and(w_classes::$field_etime, SqlOperator::Equals, $etime);
        }
        if (isset($sort) && isset($sortname)) {
            $classes->set_order_by($classes->get_field_by_name($sortname), $sort);
        } else {
            $classes->set_order_by(w_classes::$field_cid, SqlSortType::Asc);
        }
        $classes->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $classes, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取考勤配置信息失败，请重试');
        $models = Model::list_to_array($result['models']);
        echo_list_result($result, $models);
    }
    if ($action == 2) {
        $res_employees = array();
        foreach (get_employees() as $d) {
            if ($d['username'] !== "admin") {
                array_push($res_employees, array('id' => $d['username'], 'text' => $d['userid']));
            }
        }
        echo_result(array("list" => $res_employees));
    }
    if ($action == 3) {
        $classes = new w_classes();
        $db = create_pdo();
        $result = Model::query_list($db, $classes, NULL, true);
        $result_model = array();
        $models = Model::list_to_array($result['models'], array(), function($d) use(&$result_model) {
                    $stime = (str_length($d['stime']) === 3) ? str_replace(substr($d['stime'], 0, 1), substr($d['stime'], 0, 1) . ':', $d['stime']) : str_replace(substr($d['stime'], 0, 2), substr($d['stime'], 0, 2) . ':', $d['stime']);
                    $etime = (str_length($d['etime']) === 3) ? str_replace(substr($d['etime'], 0, 1), substr($d['etime'], 0, 1) . ':', $d['etime']) : str_replace(substr($d['etime'], 0, 2), substr($d['etime'], 0, 2) . ':', $d['etime']);
                    array_push($result_model, array('text' => $d['cid'], 'desc' => $stime . "-" . $etime));
                });
        echo_result(array('list' => $result_model));
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $classesData = request_object();
    /**
     * 添加
     */
    if ($action == 1) {
        $classes = new w_classes();
        $classes->set_field_from_array($classesData);
        $db = create_pdo();
        $result = $classes->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '班次已经存在或考勤配置信息添加失败~');
        echo_msg('考勤配置添加成功~');
    }
    /**
     * 删除
     */
    if ($action == 2) {
        $classes = new w_classes();
        $classes->set_field_from_array($classesData);
        $db = create_pdo();
        $result = $classes->delete($db);
        if (!$result[0]) die_error(USER_ERROR, '考勤配置信息删除失败~');
        echo_msg('考勤配置删除成功~');
    }
    /**
     * 修改
     */
    if ($action == 3) {
        $classes = new w_classes();
        $classes->set_field_from_array($classesData);
        $db = create_pdo();
        $result = $classes->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '修改考勤配置信息失败~');
        echo_msg('修改考勤配置成功~');
    }
});
