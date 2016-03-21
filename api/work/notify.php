<?php

/**
 * 周报列表
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/3/21
 */
use Models\Base\Model;
use Models\S_Notify;
use Models\Base\SqlOperator;
require '../../common/http.php';

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $db = create_pdo();
    if (!isset($action))$action = -1;
    $notify = new S_Notify();
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    filter_numeric($status, 1);
    if (isset($searchName)) {
        $notify->set_where(S_Notify::$field_username, SqlOperator::Like, '%' . $searchName . '%');
    }
    if (isset($sort) && isset($sortname)) {
        $notify->set_order_by($notify->get_field_by_name($sortname), $sort);
    } else {
        $notify->set_order_by(S_Notify::$field_addtime, 'DESC');
    }
    $notify->set_limit_paged(request_pageno(), request_pagesize());
    $result = Model::query_list($db, $notify, NULL, true);
    if (!$result[0]) {
        die_error(USER_ERROR, '获取行政通知失败，请重试');
    }
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $notifyData = request_object();
    if ($action == 1) {
        $notify = new S_Notify();
        $notify->set_field_from_array($notifyData);
        $employee = get_employees()[request_userid()];
        $notify->set_userid(request_userid());
        $notify->set_username(request_username());
        $notify->set_addtime("now");
        $notify->set_dept1_id($employee['dept1_id']);
        $db = create_pdo();
        pdo_transaction($db, function($db) use($notify) {
            $result = $notify->insert($db);
            if (!$result[0])throw new TransactionException(PDO_ERROR_CODE, '保存行政通知失败。' . $result['detail_cn'], $result);            
        });

        $msg = json_encode(array('Content' => $notifyData->content, 'Title' => $notifyData->title, 'Code' => 0, 'MsgType' => 0));
        $result = send_push_msg($msg);
        if(!$result) die_error (USER_ERROR, "发送失败~");
        echo_msg("发送成功~");
    }
    //修改销售统计信息
    if ($action == 2) {
        $notify = new S_Notify();
        $notify->set_field_from_array($notifyData);
        $db = create_pdo();
        $result = $notify->update($db, true);
        if (!$result[0])
            die_error(USER_ERROR, '保存周报失败');
        echo_msg('保存成功');
    }
    //删除
    if ($action == 3) {
        $notify = new S_Notify();
        $notify->set_field_from_array($notifyData);
        $db = create_pdo();
        $result = $notify->delete($db, true);
        if (!$result[0])die_error(USER_ERROR, '删除失败');
        echo_msg('删除成功');
    }
});

