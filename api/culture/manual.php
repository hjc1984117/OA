<?php

/**
 * 员工手册
 *
 * @author Qi
 * @copyright 2015 星密码
 * @version 2015/5/13
 */
use Models\Base\Model;
use Models\M_Role;
use Models\E_Manual;
use Models\Base\SqlOperator;
use Models\Base\SqlSortType;

require '../../application.php';
require '../../loader-api.php';
require '../../Common/FileUpload.php';
require '../../Common/word2pdf.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() {
    $manual = new E_Manual();
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    $sysclass = request_string("sys_class");
    if (isset($searchName)) {
        $manual->set_where_and(E_Manual::$field_sys_title, SqlOperator::Like, '%' . $searchName . '%');
    }
    if (isset($sysclass)) {
        if ($sysclass == "自定义") {
            $manual->set_where_and(E_Manual::$field_sys_class, SqlOperator::NotEquals, "福利");
            $manual->set_where_and(E_Manual::$field_sys_class, SqlOperator::NotEquals, "考勤");
        } else {
            $manual->set_where_and(E_Manual::$field_sys_class, SqlOperator::Equals, $sysclass);
        }
    }
    if (isset($sort) && isset($sortname)) {
        $manual->set_order_by(E_Manual::$field_top, 'DESC');
        $manual->set_order_by(E_Manual::$field_date, 'DESC');
        $manual->set_order_by($manual->get_field_by_name($sortname), $sort);
    } else {
        $manual->set_order_by(E_Manual::$field_top, 'DESC');
        $manual->set_order_by(E_Manual::$field_date, 'DESC');
        $manual->set_order_by(E_Manual::$field_date, SqlSortType::Desc);
    }
    $manual->set_limit_paged(request_pageno(), request_pagesize());
    $db = create_pdo();
    $result = Model::query_list($db, $manual, NULL, true);
    if (!$result[0]) die_error(USER_ERROR, '获取申购资料失败，请重试');
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $manualData = request_object();
    //添加制度内容
    if ($action == 1) {
        $manual = new E_Manual();
        $manual->set_field_from_array($manualData);
        $manual->set_date("now");
        $manual->set_top(0);
        $db = create_pdo();
        $result = $manual->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加制度内容失败。');
        echo_msg('添加成功');
    }
    //修改制度内容信息
    if ($action == 2) {
        $db = create_pdo();
        $manual = new E_Manual($manualData->id);
        $manual->load($db, $manual);
        if (!isset($manualData->file_path) || !isset($manualData->pdf_file_name)) {
            del_system_files($manual);
        } else {
            if (($manualData->file_path) != ($manual->get_file_path()) || ($manualData->pdf_file_name) != ($manual->get_pdf_file_name())) {
                del_system_files($manual);
            }
        }
        $manual->set_field_from_array($manualData);
        $manual->set_top(0);
        $manual->set_date("now");
        $result = $manual->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存制度内容失败');
        echo_msg('保存成功');
    }
    //修改置顶
    if ($action == 4) {
        $usystem = new E_Manual();
        $usystem->set_where(E_Manual::$field_top, SqlOperator::Equals, 1);
        $usystem->set_top(0);
        $manual = new E_Manual();
        $manual->set_field_from_array($manualData);
        $manual->set_top(1);
        $db = create_pdo();
        pdo_transaction($db, function($db) use($usystem, $manual) {
            $result = $usystem->update($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '置顶失败', $result);
            $result = $manual->update($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '置顶失败', $result);
        });
        echo_msg('置顶成功');
    }

    if ($action == 5) {
        $usystem = new E_Manual($manualData->id);
        $db = create_pdo();
        $result = $usystem->delete($db);
        if (!$result[0]) die_error(USER_ERROR, "删除制度内容失败~");
        if (is_file(DEFAULT_FILE_UPLOAD_DIR . ($manualData->file_path))) {
            unlink(DEFAULT_FILE_UPLOAD_DIR . ($manualData->file_path));
        }
        if (is_file(DEFAULT_PDF_OUTPUT_DIR . ($manualData->pdf_file_name))) {
            unlink(DEFAULT_PDF_OUTPUT_DIR . ($manualData->pdf_file_name));
        }
        if (is_file(DEFAULT_SWF_OUTPUT_DIR . ($manualData->pdf_file_name) . '.' . 'swf')) {
            unlink(DEFAULT_SWF_OUTPUT_DIR . ($manualData->pdf_file_name) . '.' . 'swf');
        }
        echo_msg("删除制度内容成功~");
    }
});

function del_system_files($manual) {
    $files = $manual->get_file_path();
    $pdf = $manual->get_pdf_file_name();
    if (is_file(DEFAULT_FILE_UPLOAD_DIR . $files)) {
        unlink(DEFAULT_FILE_UPLOAD_DIR . $files);
    }
    if (is_file(DEFAULT_PDF_OUTPUT_DIR . $pdf)) {
        unlink(DEFAULT_PDF_OUTPUT_DIR . $pdf);
    }
    if (is_file(DEFAULT_SWF_OUTPUT_DIR . $pdf . '.' . 'swf')) {
        unlink(DEFAULT_SWF_OUTPUT_DIR . $pdf . '.' . 'swf');
    }
}
