<?php

/*
 * 考勤用户
 */

use Models\Base\Model;
use Models\Base\SqlSortType;
use Models\w_attuser;
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
        $atid = request_int('atid');
        $bid = request_int('bid');
        $aname = request_string('aname');
        $userid = request_int('userid');
        $uname = request_string('uname');
        $attuser = new w_attuser();
        if (isset($atid)) {
            $attuser->set_where_and(w_attuser::$field_atid, SqlOperator::Equals, $atid);
        }
        if (isset($bid)) {
            $attuser->set_where_and(w_attuser::$field_bid, SqlOperator::Equals, $bid);
        }
        if (isset($aname)) {
            $attuser->set_where_and(w_attuser::$field_aname, SqlOperator::Like, '%' . $aname . '%');
        }
        if (isset($userid)) {
            $attuser->set_where_and(w_attuser::$field_userid, SqlOperator::Equals, $userid);
        }
        if (isset($uname)) {
            $attuser->set_where_and(w_attuser::$field_uname, SqlOperator::Like, '%' . $uname . '%');
        }
        if (isset($sort) && isset($sortname)) {
            $attuser->set_order_by($attuser->get_field_by_name($sortname), $sort);
        } else {
            $attuser->set_order_by(w_attuser::$field_atid, SqlSortType::Asc);
        }
        $attuser->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $attuser, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取考勤用户信息失败，请重试');
        $models = Model::list_to_array($result['models']);
        echo_list_result($result, $models);
    }
    if ($action == 2) {
        $res_employees = array();
        $employees = get_employees();
        rsort($employees);
        foreach ($employees as $d) {
            if ($d['username'] !== "admin") {
                array_push($res_employees, array('id' => $d['username'], 'text' => $d['userid'] . "(" . $d['username'] . ")"));
            }
        }
        echo_result(array("list" => $res_employees));
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    if ($action == 3) {
        $attuserData = request_object();
        $attuser = new w_attuser();
        $attuser->set_field_from_array($attuserData);
        $db = create_pdo();
        $result = $attuser->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '修改考勤用户信息失败~');
        echo_msg('修改考勤用户成功~');
    }
});
