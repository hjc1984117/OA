<?php

/**
 * 申购系统
 *
 * @author YanXiong
 * @copyright 2015 星密码
 * @version 2015/1/27
 */
use Models\Base\Model;
use Models\Base\SqlOperator;
use Models\C_Assets;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $db = create_pdo();
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    $assets = new C_Assets();
    if (isset($searchName)) {
        $assets->set_where(C_Assets::$field_name, SqlOperator::Like, '%' . $searchName . '%');
    }
    if (isset($sort) && isset($sortname)) {
        $assets->set_order_by($assets->get_field_by_name($sortname), $sort);
    } else {
        $assets->set_order_by(C_Assets::$field_buy_date, 'ASC');
    }
    $assets->set_limit_paged(request_pageno(), request_pagesize());
    $result = Model::query_list($db, $assets, NULL, true);
    if (!$result[0]) die_error(USER_ERROR, '获取固定资产资料失败，请重试');
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $assetstData = request_object();
    //添加固定资产信息
    if ($action == 1) {
        $assets = new C_Assets();
        $assets->set_field_from_array($assetstData);
        $db = create_pdo();
        $result = $assets->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加固定资产失败');
        echo_msg('添加成功');
    }
    //修改支出信息
    if ($action == 2) {
        $assets = new C_Assets();
        $assets->set_field_from_array($assetstData);
        $db = create_pdo();
        $result = $assets->update($db);
        if (!$result[0]) die_error(USER_ERROR, '保存固定资产失败');
        echo_msg('保存成功');
    }

    //修改支出信息
    if ($action == 3) {
        $assets = new C_Assets();
        $assets->set_field_from_array($assetstData);
        $db = create_pdo();
        $result = $assets->delete($db);
        if (!$result[0]) die_error(USER_ERROR, '删除固定资产失败');
        echo_msg('删除成功');
    }
});
