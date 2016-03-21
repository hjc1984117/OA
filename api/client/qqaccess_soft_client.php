<?php

/**
 * QQ接入
 */
use Models\Base\Model;
use Models\p_qqaccess_soft;
use Models\Base\SqlSortType;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';
require_once '../../common/http.php';
$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $qq_access = new p_qqaccess_soft();
        $login_userid = request_login_userid();
        $is_manager = is_manager($login_userid, 2);
        if (!$is_manager) {
            $qq_access->set_custom_where(" AND ( presales_id = " . $login_userid . " OR add_userid = " . $login_userid . ") ");
        }
        $qq_access->set_custom_where(" AND '" . date('Y-m-d') . " 03:00:00' <=  DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') <= '" . date('Y-m-d', strtotime("+1 day")) . " 02:59:59'");
        $qq_access->set_custom_order_by("IF(ISNULL(access_time),0,1) ASC");
        $qq_access->set_order_by(p_qqaccess_soft::$field_addtime, SqlSortType::Desc);
        $qq_access->set_custom_order_by("hasValidation ASC");
        if (isset($sort) && isset($sortname)) {
            $qq_access->set_order_by($qq_access->get_field_by_name($sortname), $sort);
        }
        $qq_access->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $qq_access, NULL, TRUE);
        if (!$result[0]) die_error(USER_ERROR, '获取QQ接入数据失败，请重试');
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    if ((int) $d['handle_time'] === 0 || !isset($d['handle_time'])) {
                        $addTime = strtotime($d['addtime']);
                        $currentTime = strtotime(date("Y-m-d H:i:s"));
                        $d['wait_time'] = $currentTime - $addTime;
                    }
                });
        echo_list_result($result, $models);
    }
});

execute_request(HttpRequestMethod::Post, function() use ($action) {
    $qq_accessData = request_object();
    if ($action == 2) {
        $qq_access = new p_qqaccess_soft($qq_accessData->id);
        $db = create_pdo();
        $res = $qq_access->load($db, $qq_access);
        if (!$res[0]) die_error(USER_ERROR, '保存失败');
        if (isset($qq_accessData->do_access)) {
            $time = date("Y-m-d h:i:s");
            $qq_access->set_access_time($time);
            $handle_time = $qq_access->get_handle_time();
            if (!isset($handle_time)) {
                $qq_access->set_handle_time($time);
                $addTime = $qq_access->get_addtime()->format("Y-m-d H:i:s");
                $addTime = strtotime($addTime);
                $currentTime = strtotime(date("Y-m-d H:i:s"));
                $wait_time = $currentTime - $addTime;
                $qq_access->set_wait_time($wait_time);
            }
            add_data_change_log($db, $qq_accessData, new p_qqaccess_soft($qq_accessData->id), 11001, '确认通过');
        } else if (isset($qq_accessData->do_val)) {
            $qq_access->set_hasValidation($qq_accessData->hasValidation);
            $handle_time = $qq_access->get_handle_time();
            if (!isset($handle_time)) {
                $qq_access->set_handle_time("now");
                $addTime = $qq_access->get_addtime()->format("Y-m-d H:i:s");
                $addTime = strtotime($addTime);
                $currentTime = strtotime(date("Y-m-d H:i:s"));
                $wait_time = $currentTime - $addTime;
                $qq_access->set_wait_time($wait_time);
            }
            add_data_change_log($db, $qq_accessData, new p_qqaccess_soft($qq_accessData->id), 11001, '获取QQ验证');
        } else {
            $qq_access->set_field_from_array($qq_accessData);
            add_data_change_log($db, $qq_accessData, new p_qqaccess_soft($qq_accessData->id), 11001);
        }
        $result = $qq_access->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存失败');
        if ($qq_access->get_hasValidation() === 1) {
            $text = "亲，您有QQ验证消息未处理，请尽快收集验证返回给售前。[此功能不稳定，亲，请随时注意刷新哦]";
            $msg = array('step' => 2, 'title' => "QQ验证消息", 'addtime' => date("Y-m-d H:i:s"), 'username' => request_login_username(), 'caption' => 'QQ号', 'text' => $text, 'Code' => 0, 'Msg' => '', 'Remark' => '', 'MsgType' => 4);
            send_push_msg(json_encode($msg), $qq_access->get_add_userid());
        }
        if (($qq_access->get_hasValidation() === 2)) {
            $text = "亲，您有转Q消息未处理，请尽快加人确认。[此功能不稳定，亲，请随时注意刷新哦]";
            $msg = array('step' => 3, 'title' => "QQ验证消息", 'addtime' => date("Y-m-d H:i:s"), 'username' => request_login_username(), 'caption' => '验证信息', 'text' => $qq_access->get_qq_num() . "(" . $qq_access->get_validation() . ')', 'Code' => 0, 'Msg' => '', 'Remark' => '', 'MsgType' => 4);
            send_push_msg(json_encode($msg), $qq_access->get_presales_id());
        }
        echo_msg('保存成功');
    }
});
