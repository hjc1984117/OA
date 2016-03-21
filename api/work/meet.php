<?php

/**
 * 会议记录
 *
 * @author Qi
 * @copyright 2015 星密码
 * @version 2015/5/12
 */
use Models\Base\Model;
use Models\W_Meet;
use Models\Base\SqlOperator;
use Models\Base\SqlSortType;

require '../../application.php';
require '../../loader-api.php';
require '../../Common/FileUpload.php';
require '../../Common/word2pdf.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() {
    $meet = new W_Meet();
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    $sysclass = request_string("sys_class");
    if (isset($searchName)) {
        $meet->set_where_and(W_Meet::$field_sys_title, SqlOperator::Like, '%' . $searchName . '%');
    }
    if (isset($sort) && isset($sortname)) {
        $meet->set_order_by(W_Meet::$field_date, 'DESC');
        $meet->set_order_by($meet->get_field_by_name($sortname), $sort);
    } else {
        $meet->set_order_by(W_Meet::$field_date, 'DESC');
        $meet->set_order_by(W_Meet::$field_date, SqlSortType::Desc);
    }
    $meet->set_limit_paged(request_pageno(), request_pagesize());
    $db = create_pdo();
    $result = Model::query_list($db, $meet, NULL, true);
    if (!$result[0]) die_error(USER_ERROR, '获取申购资料失败，请重试');
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $meetData = request_object();
    //添加会议内容
    if ($action == 1) {
        $meet = new W_Meet();
        $meet->set_field_from_array($meetData);
        $meet->set_date("now");
        $meet->set_username(request_username());
        $meet->set_userid(request_userid());
        $db = create_pdo();
        $result = $meet->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加会议内容失败。');
        echo_msg('添加成功');
    }
    //修改会议内容信息
    if ($action == 2) {
        $db = create_pdo();
        $meet = new W_Meet($meetData->id);
        $meet->load($db, $meet);
        $meet->set_field_from_array($meetData);
        $meet->set_date("now");
        $meet->set_username(request_username());
        $meet->set_userid(request_userid());
        $result = $meet->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存会议内容失败');
        echo_msg('保存成功');
    }

    if ($action == 5) {
        $usystem = new W_Meet($meetData->id);
        $db = create_pdo();
        $result = $usystem->delete($db);
        if (!$result[0]) die_error(USER_ERROR, "删除会议内容失败~");
        echo_msg("删除会议内容成功~");
    }
});
