<?php

/**
 * 电话记录
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/10/31
 */
use Models\Base\Model;
use Models\p_phone_sys_soft;
use Models\Base\SqlOperator;
use Models\Base\SqlSortType;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../Common/http.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $searchName = request_string('searchName');
        $phone_sys = new p_phone_sys_soft();
        if (isset($searchName)) {
            $phone_sys->set_custom_where(" AND (ww phone '%" . $searchName . "%') OR use_username like '%" . $searchName . "%' OR au_name like '%" . $searchName . "%' OR bind_shop like '%" . $searchName . "%' ");
        }
        $phone_sys->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $phone_sys, NULL, true);
        if (!$result[0]) {
            die_error(USER_ERROR, '获取电话记录失败，请重试');
        }
        $models = Model::list_to_array($result['models']);
        echo_list_result($result, $models);
    }
    if ($action == 11) {
        $export = new ExportData2Excel();
        $phone_sys = new p_phone_sys_soft();
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        if (isset($startTime)) {
            $phone_sys->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $phone_sys->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') <= '" . $endTime . "' ");
        }
        $field = array('addtime', 'phone', 'city', 'area', 'phone_type', 'setmeal', 'use_username', 'bind_shop', 'used', 'is_arrearage', 'au_name');
        $phone_sys->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $phone_sys, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('电话记录导出失败,请稍后重试!')), "电话记录导出", "电话记录");
        }
        $employees = get_employees();
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($employees) {
                    $d['add_userid'] = $employees[$d['add_userid']]['username'];
                });
        $title_array = array('添加时间', '电话号码', '城市', '地区', '使用手机型号', '套餐内容', '使用人员', '绑定店铺', '用途', '是否欠费', '电话卡认证姓名 ');
        $export->set_field($field);
        $export->create($title_array, $models, "电话记录导出", "电话记录");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $phoneSysData = request_object();
    /**
     * 添加
     */
    if ($action == 1) {
        $phone_sys = new p_phone_sys_soft();
        $phone_sys->set_field_from_array($phoneSysData);
        $phone_sys->set_addtime('now');
        $db = create_pdo();
        $result = $phone_sys->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加电话记录失败~');
        echo_msg('添加电话记录成功~');
    }

    /**
     * 删除
     */
    if ($action == 2) {
        $phone_sys = new p_phone_sys_soft($phoneSysData->id);
        $db = create_pdo();
        $result = $phone_sys->delete($db);
        if (!$result[0]) die_error(USER_ERROR, '删除电话记录失败~');
        echo_msg('删除电话记录成功~');
    }

    /**
     * 修改
     */
    if ($action == 3) {
        $phone_sys = new p_phone_sys_soft($phoneSysData->id);
        $phone_sys->set_field_from_array($phoneSysData);
        $db = create_pdo();
        $result = $phone_sys->update($db, TRUE);
        if (!$result[0]) die_error(USER_ERROR, '修改电话记录失败~');
        echo_msg('修改电话记录成功~');
    }
});
