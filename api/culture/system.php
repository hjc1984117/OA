<?php

/**
 * 员工列表/员工增删改操作
 *
 * @author Qi
 * @copyright 2015 星密码
 * @version 2015/3/3
 */
use Models\Base\Model;
use Models\M_Role;
use Models\E_System;
use Models\Base\SqlOperator;
use Models\Base\SqlSortType;

require '../../application.php';
require '../../loader-api.php';
require '../../Common/FileUpload.php';
require '../../Common/word2pdf.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() {
    $system = new E_System();
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    $sysclass = request_string("sys_class");
    if (isset($searchName)) {
        $system->set_where_and(E_System::$field_sys_title, SqlOperator::Like, '%' . $searchName . '%');
    }
    if (isset($sysclass)) {
        if ($sysclass == "自定义") {
            $system->set_where_and(E_System::$field_sys_class, SqlOperator::NotEquals, "福利");
            $system->set_where_and(E_System::$field_sys_class, SqlOperator::NotEquals, "考勤");
        } else {
            $system->set_where_and(E_System::$field_sys_class, SqlOperator::Equals, $sysclass);
        }
    }
    if (isset($sort) && isset($sortname)) {
        $system->set_order_by(E_System::$field_top, 'DESC');
        $system->set_order_by(E_System::$field_date, 'DESC');
        $system->set_order_by($system->get_field_by_name($sortname), $sort);
    } else {
        $system->set_order_by(E_System::$field_top, 'DESC');
        $system->set_order_by(E_System::$field_date, 'DESC');
        $system->set_order_by(E_System::$field_date, SqlSortType::Desc);
    }
    $system->set_limit_paged(request_pageno(), request_pagesize());
    $db = create_pdo();
    $result = Model::query_list($db, $system, NULL, true);
    if (!$result[0]) die_error(USER_ERROR, '获取申购资料失败，请重试');
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $systemData = request_object();
    //添加制度内容
    if ($action == 1) {
        $system = new E_System();
        $system->set_field_from_array($systemData);
        $system->set_date("now");
        $system->set_top(0);
        $db = create_pdo();
        $result = $system->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加制度内容失败。');
        echo_msg('添加成功');
    }
    //修改制度内容信息
    if ($action == 2) {
        $db = create_pdo();
        $system = new E_System($systemData->id);
        $system->load($db, $system);
        if (!isset($systemData->file_path) || !isset($systemData->pdf_file_name)) {
            del_system_files($system);
        } else {
            if (($systemData->file_path) != ($system->get_file_path()) || ($systemData->pdf_file_name) != ($system->get_pdf_file_name())) {
                del_system_files($system);
            }
        }
        $system->set_field_from_array($systemData);
        $system->set_top(0);
        $system->set_date("now");
        $result = $system->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存制度内容失败');
        echo_msg('保存成功');
    }
    //修改置顶
    if ($action == 4) {
        $usystem = new E_System();
        $usystem->set_where(E_System::$field_top, SqlOperator::Equals, 1);
        $usystem->set_top(0);
        $system = new E_System();
        $system->set_field_from_array($systemData);
        $system->set_top(1);
        $db = create_pdo();
        pdo_transaction($db, function($db) use($usystem, $system) {
            $result = $usystem->update($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '置顶失败', $result);
            $result = $system->update($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '置顶失败', $result);
        });
        echo_msg('置顶成功');
    }

    if ($action == 5) {
        $usystem = new E_System($systemData->id);
        $db = create_pdo();
        $result = $usystem->delete($db);
        if (!$result[0]) die_error(USER_ERROR, "删除制度内容失败~");
        if (is_file(DEFAULT_FILE_UPLOAD_DIR . ($systemData->file_path))) {
            unlink(DEFAULT_FILE_UPLOAD_DIR . ($systemData->file_path));
        }
        if (is_file(DEFAULT_PDF_OUTPUT_DIR . ($systemData->pdf_file_name))) {
            unlink(DEFAULT_PDF_OUTPUT_DIR . ($systemData->pdf_file_name));
        }
        if (is_file(DEFAULT_SWF_OUTPUT_DIR . ($systemData->pdf_file_name) . '.' . 'swf')) {
            unlink(DEFAULT_SWF_OUTPUT_DIR . ($systemData->pdf_file_name) . '.' . 'swf');
        }
        echo_msg("删除制度内容成功~");
    }
});

function del_system_files($system) {
    $files = $system->get_file_path();
    $pdf = $system->get_pdf_file_name();
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
