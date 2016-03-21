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
use Models\E_Word;
use Models\Base\SqlOperator;
use Models\Base\SqlSortType;

require '../../application.php';
require '../../loader-api.php';
require '../../Common/FileUpload.php';
require '../../Common/word2pdf.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() {
    $word = new E_Word();
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    $sysclass = request_string("sys_class");
    if (isset($searchName)) {
        $word->set_where_and(E_Word::$field_sys_title, SqlOperator::Like, '%' . $searchName . '%');
    }
    if (isset($sysclass)) {
        if ($sysclass == "自定义") {
            $word->set_where_and(E_Word::$field_sys_class, SqlOperator::NotEquals, "福利");
            $word->set_where_and(E_Word::$field_sys_class, SqlOperator::NotEquals, "考勤");
        } else {
            $word->set_where_and(E_Word::$field_sys_class, SqlOperator::Equals, $sysclass);
        }
    }
    if (isset($sort) && isset($sortname)) {
        $word->set_order_by(E_Word::$field_top, 'DESC');
        $word->set_order_by(E_Word::$field_date, 'DESC');
        $word->set_order_by($word->get_field_by_name($sortname), $sort);
    } else {
        $word->set_order_by(E_Word::$field_top, 'DESC');
        $word->set_order_by(E_Word::$field_date, 'DESC');
        $word->set_order_by(E_Word::$field_date, SqlSortType::Desc);
    }
    $word->set_limit_paged(request_pageno(), request_pagesize());
    $db = create_pdo();
    $result = Model::query_list($db, $word, NULL, true);
    if (!$result[0]) die_error(USER_ERROR, '获取申购资料失败，请重试');
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $wordData = request_object();
    //添加制度内容
    if ($action == 1) {
        $word = new E_Word();
        $word->set_field_from_array($wordData);
        $word->set_date("now");
        $word->set_top(0);
        $db = create_pdo();
        $result = $word->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加制度内容失败。');
        echo_msg('添加成功');
    }
    //修改制度内容信息
    if ($action == 2) {
        $db = create_pdo();
        $word = new E_Word($wordData->id);
        $word->load($db, $word);
        if (!isset($wordData->file_path) || !isset($wordData->pdf_file_name)) {
            del_word_files($word);
        } else {
            if (($wordData->file_path) != ($word->get_file_path()) || ($wordData->pdf_file_name) != ($word->get_pdf_file_name())) {
                del_word_files($word);
            }
        }
        $word->set_field_from_array($wordData);
        $word->set_top(0);
        $word->set_date("now");
        $result = $word->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存制度内容失败');
        echo_msg('保存成功');
    }
    //修改置顶
    if ($action == 4) {
        $uword = new E_Word();
        $uword->set_where(E_Word::$field_top, SqlOperator::Equals, 1);
        $uword->set_top(0);
        $word = new E_Word();
        $word->set_field_from_array($wordData);
        $word->set_top(1);
        $db = create_pdo();
        pdo_transaction($db, function($db) use($uword, $word) {
            $result = $uword->update($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '置顶失败', $result);
            $result = $word->update($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '置顶失败', $result);
        });
        echo_msg('置顶成功');
    }

    if ($action == 5) {
        $uword = new E_Word($wordData->id);
        $db = create_pdo();
        $result = $uword->delete($db);
        if (!$result[0]) die_error(USER_ERROR, "删除制度内容失败~");
        if (is_file(DEFAULT_FILE_UPLOAD_DIR . ($wordData->file_path))) {
            unlink(DEFAULT_FILE_UPLOAD_DIR . ($wordData->file_path));
        }
        if (is_file(DEFAULT_PDF_OUTPUT_DIR . ($wordData->pdf_file_name))) {
            unlink(DEFAULT_PDF_OUTPUT_DIR . ($wordData->pdf_file_name));
        }
        if (is_file(DEFAULT_SWF_OUTPUT_DIR . ($wordData->pdf_file_name) . '.' . 'swf')) {
            unlink(DEFAULT_SWF_OUTPUT_DIR . ($wordData->pdf_file_name) . '.' . 'swf');
        }
        echo_msg("删除制度内容成功~");
    }
});

function del_word_files($word) {
    $files = $word->get_file_path();
    $pdf = $word->get_pdf_file_name();
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
