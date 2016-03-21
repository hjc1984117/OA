<?php

/**
 * 周报列表
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/3/21
 */
use Models\Base\Model;
use Models\S_Doamin;
use Models\Base\SqlOperator;
use Models\S_ExpireReminder;

require '../../common/ExportData2Excel.php';
require '../../common/http.php';
require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');

        $name = request_string('name');
        $owner = request_string('owner');
        $startDueDate = request_string('startDueDate');
        $endDueDate = request_string('endDueDate');
        $useed = request_string('useed');
        $isBurn = request_string('isBurn');
        $recordPerson = request_string('recordPerson');
        $recordSituation = request_string('recordSituation');
        $recordType = request_string('recordType');
        $nameType = request_string('nameType');
        $nameBusiness = request_string('nameBusiness');
        $buyAccount = request_string('buyAccount');
        $category = request_string('category');
        $service = request_string('service');
        $ipAddress = request_string('ipAddress');
        $doamin = new S_Doamin();
        if (isset($searchName)) {
            $doamin->set_custom_where(" AND (  name LIKE '%" . $searchName . "%' OR service LIKE '%" . $searchName . "%' OR ipAddress LIKE '%" . $searchName . "%' OR owner LIKE '%" . $searchName . "%' OR useed LIKE '%" . $searchName . "%' OR isBurn LIKE '%" . $searchName . "%'  OR recordPerson LIKE '%" . $searchName . "%' OR recordSituation LIKE '%" . $searchName . "%' OR recordType LIKE '%" . $searchName . "%' OR nameType LIKE '%" . $searchName . "%' OR nameBusiness LIKE '%" . $searchName . "%' OR buyAccount LIKE '%" . $searchName . "%' OR category LIKE '%" . $searchName . "%' )  ");
        }
        if (isset($name)) {
            $doamin->set_where_and(S_Doamin::$field_name, SqlOperator::Like, '%' . $name . '%');
        }
        if (isset($owner)) {
            $doamin->set_where_and(S_Doamin::$field_owner, SqlOperator::Like, '%' . $owner . '%');
        }
        if (isset($startDueDate)) {
            $doamin->set_custom_where(" AND DATE_FORMAT(dueDate,'%Y-%m-%d') >= '" . $startDueDate . "' ");
        }
        if (isset($endDueDate)) {
            $doamin->set_custom_where(" AND DATE_FORMAT(dueDate,'%Y-%m-%d') <= '" . $endDueDate . "' ");
        }
        if (isset($useed)) {
            $doamin->set_where_and(S_Doamin::$field_useed, SqlOperator::Like, '%' . $useed . '%');
        }
        if (isset($isBurn)) {
            $doamin->set_where_and(S_Doamin::$field_isBurn, SqlOperator::Like, '%' . $isBurn . '%');
        }
        if (isset($recordPerson)) {
            $doamin->set_where_and(S_Doamin::$field_recordPerson, SqlOperator::Like, '%' . $recordPerson . '%');
        }
        if (isset($recordSituation)) {
            $doamin->set_where_and(S_Doamin::$field_recordSituation, SqlOperator::Like, '%' . $recordSituation . '%');
        }
        if (isset($recordType)) {
            $doamin->set_where_and(S_Doamin::$field_recordType, SqlOperator::Like, '%' . $recordType . '%');
        }
        if (isset($nameType)) {
            $doamin->set_where_and(S_Doamin::$field_nameType, SqlOperator::Like, '%' . $nameType . '%');
        }
        if (isset($nameBusiness)) {
            $doamin->set_where_and(S_Doamin::$field_nameBusiness, SqlOperator::Like, '%' . $nameBusiness . '%');
        }
        if (isset($buyAccount)) {
            $doamin->set_where_and(S_Doamin::$field_buyAccount, SqlOperator::Like, '%' . $buyAccount . '%');
        }
        if (isset($category)) {
            $doamin->set_where_and(S_Doamin::$field_category, SqlOperator::Like, '%' . $category . '%');
        }
        if (isset($service)) {
            $doamin->set_where_and(S_Doamin::$field_service, SqlOperator::Like, '%' . $service . '%');
        }
        if (isset($ipAddress)) {
            $doamin->set_where_and(S_Doamin::$field_ipAddress, SqlOperator::Like, '%' . $ipAddress . '%');
        }
        if (isset($sort) && isset($sortname)) {
            $doamin->set_order_by($doamin->get_field_by_name($sortname), $sort);
        } else {
            $doamin->set_order_by(S_Doamin::$field_id, 'DESC');
        }
        $doamin->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $doamin, NULL, true);
        if (!$result[0]) {
            die_error(USER_ERROR, '获取行政通知失败，请重试');
        }
        $models = Model::list_to_array($result['models']);
        echo_list_result($result, $models);
    }
    if ($action == 11) {
        $export = new ExportData2Excel();
        $name = request_string('name');
        $owner = request_string('owner');
        $startDueDate = request_string('startDueDate');
        $endDueDate = request_string('endDueDate');
        $useed = request_string('useed');
        $isBurn = request_string('isBurn');
        $recordPerson = request_string('recordPerson');
        $recordSituation = request_string('recordSituation');
        $recordType = request_string('recordType');
        $nameType = request_string('nameType');
        $nameBusiness = request_string('nameBusiness');
        $buyAccount = request_string('buyAccount');
        $category = request_string('category');
        $service = request_string('service');
        $ipAddress = request_string('ipAddress');
        $doamin = new S_Doamin();
        if (isset($name)) {
            $doamin->set_where_and(S_Doamin::$field_name, SqlOperator::Like, '%' . $name . '%');
        }
        if (isset($owner)) {
            $doamin->set_where_and(S_Doamin::$field_owner, SqlOperator::Like, '%' . $owner . '%');
        }
        if (isset($startDueDate)) {
            $doamin->set_custom_where(" AND DATE_FORMAT(dueDate,'%Y-%m-%d') >= '" . $startDueDate . "' ");
        }
        if (isset($endDueDate)) {
            $doamin->set_custom_where(" AND DATE_FORMAT(dueDate,'%Y-%m-%d') <= '" . $endDueDate . "' ");
        }
        if (isset($useed)) {
            $doamin->set_where_and(S_Doamin::$field_useed, SqlOperator::Like, '%' . $useed . '%');
        }
        if (isset($isBurn)) {
            $doamin->set_where_and(S_Doamin::$field_isBurn, SqlOperator::Like, '%' . $isBurn . '%');
        }
        if (isset($recordPerson)) {
            $doamin->set_where_and(S_Doamin::$field_recordPerson, SqlOperator::Like, '%' . $recordPerson . '%');
        }
        if (isset($recordSituation)) {
            $doamin->set_where_and(S_Doamin::$field_recordSituation, SqlOperator::Like, '%' . $recordSituation . '%');
        }
        if (isset($recordType)) {
            $doamin->set_where_and(S_Doamin::$field_recordType, SqlOperator::Like, '%' . $recordType . '%');
        }
        if (isset($nameType)) {
            $doamin->set_where_and(S_Doamin::$field_nameType, SqlOperator::Like, '%' . $nameType . '%');
        }
        if (isset($nameBusiness)) {
            $doamin->set_where_and(S_Doamin::$field_nameBusiness, SqlOperator::Like, '%' . $nameBusiness . '%');
        }
        if (isset($buyAccount)) {
            $doamin->set_where_and(S_Doamin::$field_buyAccount, SqlOperator::Like, '%' . $buyAccount . '%');
        }
        if (isset($category)) {
            $doamin->set_where_and(S_Doamin::$field_category, SqlOperator::Like, '%' . $category . '%');
        }
        if (isset($service)) {
            $doamin->set_where_and(S_Doamin::$field_service, SqlOperator::Like, '%' . $service . '%');
        }
        if (isset($ipAddress)) {
            $doamin->set_where_and(S_Doamin::$field_ipAddress, SqlOperator::Like, '%' . $ipAddress . '%');
        }
        $fields = array('name', 'owner', 'dueDate', 'useed', 'isBurn', 'recordPerson', 'recordSituation', 'recordType', 'nameType', 'nameBusiness', 'buyAccount', 'category', 'service', 'ipAddress');
        $doamin->set_query_fields($fields);
        $db = create_pdo();
        $result = Model::query_list($db, $doamin, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('域名数据导出失败,请稍后重试!')), "域名数据导出", "域名");
        }
        $models = Model::list_to_array($result['models']);
        $title_array = array('域名', '域名所有者', '域名到期日期', '域名用途', '是否在烧', '备案人', '备案情况', '备案类型', '域名类型', '域名商', '购买账号', '类别', '服务器', 'IP');
        $export->set_field($fields);
//        $export->set_field_width(array());
        $export->create($title_array, $models, "销售统计数据导出", "销售统计");
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $domainData = request_object();
    //添加
    if ($action == 1) {
        $models = array();
        $names = explode(",", $domainData->name);
        foreach ($names as $val) {
            $d_e = array();
            $domain = new S_Doamin();
            $domain->set_field_from_array($domainData);
            $domain->set_name($val);
            array_push($d_e, $domain);

            $expireReminder = new S_ExpireReminder();
            $expireReminder->set_account($val);
            $expireReminder->set_useed($domainData->useed);
            $expireReminder->set_type('域名');
            $expireReminder->set_dueDate($domainData->dueDate);
            array_push($d_e, $expireReminder);
            array_push($models, $d_e);
        }
        $db = create_pdo();
        pdo_transaction($db, function($db) use($models) {
            foreach ($models as $model) {
                $domain = $model[0];
                $expireReminder = $model[1];
                $result = $domain->insert($db);
                if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '添加域名信息失败。' . $result['detail_cn'], $result);
                $expireReminder->set_p_id($domain->get_id());
                $result_ex = $expireReminder->insert($db);
                if (!$result_ex[0]) throw new TransactionException(PDO_ERROR_CODE, '添加域名信息失败。' . $result_ex['detail_cn'], $result_ex);
            }
        });
        echo_msg('添加域名信息成功~');
    }
    //修改
    if ($action == 2) {
        $domain = new S_Doamin();
        $domain->set_field_from_array($domainData);
        $db = create_pdo();
        pdo_transaction($db, function($db) use($domain, $expireReminder, $domainData) {
            $result = $domain->update($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '修改域名信息失败。' . $result['detail_cn'], $result);
            $expireReminder = new S_ExpireReminder();
            $expireReminder->set_where_and(S_ExpireReminder::$field_p_id, SqlOperator::Equals, $domain->get_id());
            $expireReminder->set_dueDate($domainData->dueDate);
            $result_ex = $expireReminder->update($db, true);
            if (!$result_ex[0]) throw new TransactionException(PDO_ERROR_CODE, '修改域名信息失败。' . $result_ex['detail_cn'], $result_ex);
        });
        echo_msg('保存域名信息成功');
    }
    if ($action == 5) {
        $domain = new S_Doamin();
        $domain->set_where_and(S_Doamin::$field_name, SqlOperator::In, explode(",", $domainData->d_name));
        $domain->set_field_from_array($domainData);

        $expireReminder = new S_ExpireReminder();
        $expireReminder->set_where_and(S_ExpireReminder::$field_account, SqlOperator::In, explode(",", $domainData->d_name));
        $expireReminder->set_dueDate($domainData->dueDate);
        $db = create_pdo();
        pdo_transaction($db, function($db) use($domain, $expireReminder) {
            $result = $domain->update($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '修改域名信息失败。' . $result['detail_cn'], $result);
            $result_ex = $expireReminder->update($db, true);
            if (!$result_ex[0]) throw new TransactionException(PDO_ERROR_CODE, '修改域名信息失败。' . $result_ex['detail_cn'], $result_ex);
        });
        echo_msg('保存域名信息成功');
    }
    //删除
    if ($action == 3) {
        $domain = new S_Doamin();
        $domain->set_field_from_array($domainData);
        $expireReminder = new S_ExpireReminder();
        $expireReminder->set_where_and(S_ExpireReminder::$field_p_id, SqlOperator::Equals, $domainData->id);
        $db = create_pdo();
        $res = $expireReminder->load($db, $expireReminder);
        if (!$res[0]) die_error(USER_ERROR, "获取域名信息失败,请稍后重试~");
        pdo_transaction($db, function($db) use($domain, $expireReminder) {
            $result = $domain->delete($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '删除域名信息失败。' . $result['detail_cn'], $result);
            $result_ex = $expireReminder->delete($db, true);
            if (!$result_ex[0]) throw new TransactionException(PDO_ERROR_CODE, '删除域名信息失败。' . $result_ex['detail_cn'], $result_ex);
        });
        echo_msg('删除域名信息成功');
    }
});

