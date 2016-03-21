<?php

/**
 * QQ接待名单
 *
 */
use Models\Base\Model;
use Models\p_qqreception_soft;
use Models\Base\SqlSortType;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if (!isset($action)) $action = -1;
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    $channel = request_string('searchChannel');
    $sql = "SELECT pr.id,pr.`status`,pr.`status0`,pr.addtime,pr.presales,pr.presales_id,pr.toplimit,pa.finish,pr.starttime,pr.endtime ";
    $sql .= "FROM p_qqreception_soft pr LEFT JOIN ( ";
    $sql .="SELECT COUNT(pa.presales_id) AS finish,pa.presales_id FROM p_qqaccess_soft pa WHERE " . getWhereSql('pa') . " GROUP BY pa.presales_id ";
    $sql .=") AS pa ON pr.presales_id = pa.presales_id WHERE 1=1 ";
    if (isset($searchName)) {
        $sql .= " AND pr.presales like '%" . $searchName . "%' ";
    }
    if (isset($channel)) {
        $sql.=" AND pr.presales_id IN (SELECT m.userid FROM m_user m WHERE m.role_id = " . $channel . ")";
    }
    if (isset($sort) && isset($sortname)) {
        if (str_equals($sortname, 'finish')) {
            $sql .= "ORDER BY pa." . $sortname . " " . $sort;
        } else {
            $sql .= "ORDER BY pr." . $sortname . " " . $sort;
        }
    } else {
        $sql .= "ORDER BY pr.status ASC";
    }
    $db = create_pdo();
    $result_total_count = Model::execute_custom_sql($db, "select COUNT(*) c from (" . $sql . ") xxx");
    if (!$result_total_count[0]) die_error(USER_ERROR, '获取QQ接待名单失败，请重试');
    $result_total_count = $result_total_count['results'][0]['c'];
    $sql .= " LIMIT " . request_pagesize() * (request_pageno() - 1) . "," . request_pagesize();
    $qq_reception = new p_qqreception_soft();
    $result = Model::query_list($db, $qq_reception, $sql);
    if (!$result[0]) die_error(USER_ERROR, '获取QQ接待名单失败，请重试');
    $models = Model::list_to_array($result['models']);
    echo_result(array('total_count' => $result_total_count, 'list' => $models, 'page_no' => request_pageno(), 'max_page_no' => ceil($result_total_count / request_pagesize())));
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $qq_receptionData = request_object();
    if ($action == 1) {
         $db = create_pdo();
        //验证重复数据
        $qq_reception = new p_qqreception_soft();
        $qq_reception->set_custom_where(" AND (presales = '".$qq_receptionData->presales."') ");
        $qq_reception->set_query_fields(array('id', 'presales', 'presales_id'));
        $result = Model::query_list($db, $qq_reception, NULL, true);
        if ($result['count'] > 0) die_error(USER_ERROR, "不能重复添加，添加失败~");
        
        $qq_reception = new p_qqreception_soft();
        $qq_reception->set_field_from_array($qq_receptionData);
        $qq_reception->set_addtime('now');
       
        $result = $qq_reception->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加失败~');
        echo_msg('添加成功~');
    }
    //修改销售售后信息
    if ($action == 2) {
        $qq_reception = new p_qqreception_soft();
        $qq_reception->set_field_from_array($qq_receptionData);
        $db = create_pdo();
        $result = $qq_reception->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存售后资料失败');
        echo_msg('保存成功');
    }
    if ($action == 5) {
        $qq_reception = new p_qqreception_soft($qq_receptionData->id);
        $qq_reception->set_field_from_array($qq_receptionData);
        $toplimit = $qq_receptionData->toplimit;
        $finish = $qq_receptionData->finish;
        if ($toplimit <= $finish) {
            die_error(USER_ERROR, '用户到达接单上限');
            exit;
        }
        $db = create_pdo();
        $result = $qq_reception->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存售后资料失败');
        echo_msg('保存成功');
    }
    //删除
    if ($action == 3) {
        $qq_reception = new p_qqreception_soft();
        $qq_reception->set_field_from_array($qq_receptionData);
        $db = create_pdo();
        $result = $qq_reception->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除失败');
        echo_msg('删除成功');
    }
    //全部启用
    if ($action == 4) {
        $db = create_pdo();
        $sql = "update p_qqreception_soft SET status = 1 where status0 = 1";
        pdo_transaction($db, function($db) use($sql) {
            $result = Model::execute_custom_sql($db, $sql);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '启动失败~' . $result['detail_cn'], $result);
        });
        echo_msg('启用成功');
    }
    //上下班
    if ($action == 30) {
        $db = create_pdo();
        $sql = "update p_qqreception_soft SET status0 = " . $qq_receptionData->status0;
        if (($qq_receptionData->status0) === 0) {
            $sql .= ",status = 0 ";
        }
        if (($qq_receptionData->status0) === 1) {
            $sql .= ",status = 1 ";
        }
        $sql .=" where id = " . $qq_receptionData->id;
        pdo_transaction($db, function($db) use($sql, $qq_receptionData) {
            add_data_change_log($db, $qq_receptionData, new p_qqreception_soft($qq_receptionData->id), 10003, (($qq_receptionData->status0) === 0 ? "下班" : "上班"));
            $result = Model::execute_custom_sql($db, $sql);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '启动失败~' . $result['detail_cn'], $result);
        });
        echo_msg((($qq_receptionData->status0) === 0 ? "下班" : "上班") . '成功');
    }
    //启用/停用
    if ($action == 40) {
        $db = create_pdo();
        $sql = "update p_qqreception_soft SET status = " . $qq_receptionData->status . " where id = " . $qq_receptionData->id;
        pdo_transaction($db, function($db) use($sql, $qq_receptionData) {
            add_data_change_log($db, $qq_receptionData, new p_qqreception_soft($qq_receptionData->id), 10002, (($qq_receptionData->status) === 0 ? "暂停" : "启用"));
            $result = Model::execute_custom_sql($db, $sql);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '启动失败~' . $result['detail_cn'], $result);
        });
        echo_msg((($qq_receptionData->status) === 0 ? "暂停" : "启用") . '成功');
    }
    //全部暂停
    if ($action == 41) {
        $db = create_pdo();
        $sql = "update p_qqreception_soft SET status = 0";
        pdo_transaction($db, function($db) use($sql) {
            $result = Model::execute_custom_sql($db, $sql);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '暂停失败~' . $result['detail_cn'], $result);
        });
        echo_msg('启用成功');
    }
});

