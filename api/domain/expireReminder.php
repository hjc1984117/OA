<?php

/**
 * 到期提醒
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/8/16
 */
use Models\Base\Model;
use Models\S_ExpireReminder;
use Models\Base\SqlOperator;

require '../../common/http.php';
require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');
        $type = request_string('type');
        $expireReminder = new S_ExpireReminder();
        if (isset($searchName)) {
            $expireReminder->set_custom_where(" AND (  type LIKE '%" . $searchName . "%' OR `useed` LIKE '%" . $searchName . "%' OR account LIKE '%" . $searchName . "%'  )  ");
        }
        if (isset($type)) {
            $expireReminder->set_where_and(S_ExpireReminder::$field_type, SqlOperator::Equals, $type);
        }
        if (isset($sort) && isset($sortname)) {
            $expireReminder->set_order_by($expireReminder->get_field_by_name($sortname), $sort);
        } else {
            $expireReminder->set_order_by(S_ExpireReminder::$field_dueDate, 'ASC');
        }
        $expireReminder->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $expireReminder, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取到期提醒信息失败,请稍后重试~');
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $d['countdown'] = timediff(($d['dueDate']) . ' 00:00:00', date("Y-m-d H:i:s"));
                });
        echo_list_result($result, $models);
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $expireRData = request_object();
    //添加
    if ($action == 1) {
        $domain = new S_ExpireReminder();
        $domain->set_field_from_array($expireRData);
        $db = create_pdo();
        $result = $domain->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加到期提醒信息失败');
        echo_msg('添加到期提醒信息成功');
    }
    //修改
    if ($action == 2) {
        $domain = new S_ExpireReminder();
        $domain->set_field_from_array($expireRData);
        $db = create_pdo();
        $result = $domain->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存到期提醒信息失败');
        echo_msg('保存到期提醒信息成功');
    }
    //删除
    if ($action == 3) {
        $domain = new S_ExpireReminder();
        $domain->set_field_from_array($expireRData);
        $db = create_pdo();
        $result = $domain->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除到期提醒信息失败');
        echo_msg('删除到期提醒信息成功');
    }
});

function timediff($date1, $date2) {
    $date1 = strtotime($date1);
    $date2 = strtotime($date2);
    if ($date1 <= $date2) {
        return "已过期";
    }
    $days = ceil(($date1 - $date2) / 60);
    return $days;
}
