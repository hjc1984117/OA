<?php

/**
 * 员工 个人中心
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/6/18
 */
use Models\Base\Model;
use Models\Base\SqlOperator;
use Models\I_Talk;
use Models\I_Comment;
use Models\Base\SqlSortType;
USE Models\M_UserToken;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $talk = new I_Talk();
        $sql = "SELECT it.*,ut.avatar,ut.exp from I_Talk it LEFT JOIN M_UserToken ut ";
        $sql .="ON it.userid = ut.userid WHERE it.type = 0 ORDER BY it.addtime DESC ";
        $sql .=" LIMIT " . request_pagesize() * (request_pageno() - 1) . "," . request_pagesize();
        $db = create_pdo();
        $result = Model::query_list($db, $talk, $sql);
        if (!$result[0]) die_error(USER_ERROR, '获取动态失败，请重试');
        $talk_ids = "";
        /* @var $model I_Talk */
        foreach ($result['models'] as $model) {
            $talk_ids .= $model->get_id() . ",";
        }
        $comment = new I_Comment();
        $com_sql = "SELECT * FROM I_Comment ic LEFT JOIN M_UserToken ut ON ic.userid = ut.userid where ic.talk_id in (" . substr($talk_ids, 0, strlen($talk_ids) - 1) . ") ORDER BY ic.addtime ASC ";
        $comment_result = Model::query_list($db, $comment, $com_sql);
        $models = array();
        if ($comment_result[0]) {
            $comment_models = Model::list_to_array($comment_result['models']);
            $models = Model::list_to_array($result['models'], array(), function (&$d) use ($comment_models) {
                        foreach ($comment_models as &$model) {
                            if ($d['id'] == $model['talk_id']) {
                                if ($model['comment_id'] == 0) {
                                    $d['comment'][] = $model;
                                    $d['reply_num'] = $d['reply_num'] + 1;
                                } else {
                                    foreach ($d['comment'] as &$comment) {
                                        if ($comment['id'] == $model['comment_id']) {
                                            $comment['comment'][] = $model;
                                        }
                                    }
                                }
                            }
                        }
                    });
        }
        $user_token = new M_UserToken(request_login_userid());
        $user_token->load($db, $user_token);
        $talk->reset();
        $total = Model::model_count($db, $talk);
        $max_page_no = ceil($total / request_pagesize());
        $array = array('p_a' => $d_array, 'current_date' => date('Y-m-d'), "avatar" => $user_token->get_avatar(), 'total_count' => (int) $total, 'max_page_no' => $max_page_no, 'code' => 0, 'list' => $models, 'page_no' => request_pageno());
        echo_result($array);
    }
    if ($action == 2) {
        $talk = new I_Talk();
        $sql = "SELECT it.*,ut.avatar,ut.exp from I_Talk it LEFT JOIN M_UserToken ut ";
        $sql .="ON it.userid = ut.userid WHERE it.type = 1 ORDER BY it.addtime DESC ";
        $sql .=" LIMIT 0,1";
        $db = create_pdo();
        $result = $talk->load($db, $talk, $sql);
        if ($result[0]) {
            $comment = new I_Comment();
            $com_sql = "SELECT * FROM I_Comment ic LEFT JOIN M_UserToken ut ON ic.userid = ut.userid where ic.talk_id =" . $talk->get_id() . " ORDER BY ic.addtime ASC ";
            $comment_result = Model::query_list($db, $comment, $com_sql);
            if ($comment_result[0]) {
                $comment_models = Model::list_to_array($comment_result['models']);
                $talk = $talk->to_array();
                foreach ($comment_models as &$model) {
                    if ($talk['id'] == $model['talk_id']) {
                        if ($model['comment_id'] == 0) {
                            $talk['comment'][] = $model;
                            $talk['reply_num'] = $talk['reply_num'] + 1;
                        } else {
                            foreach ($talk['comment'] as &$comment) {
                                if ($comment['id'] == $model['comment_id']) {
                                    $comment['comment'][] = $model;
                                }
                            }
                        }
                    }
                }
            }
            echo_result(array('current_date' => date('Y-m-d'), 'list' => $talk, 'code' => 0, 'msg' => '获取每日分享成功~'));
        }
    }
    if ($action == 3) {
        $login_userid = request_login_userid();
        $login_dept_id = get_dept_id($login_userid);
        $d_array = array('is_manager' => false, 'show_btn' => false, 'url' => '');
        $manager_role_ids = array(101, 102, 103, 401, 601, 701, 702, 709, 801, 802, 803, 804, 901, 1101, 1201, 1301);
        $manager_userids = array(1);
        $is_manager = in_array(get_role_id($login_userid), $manager_role_ids) || in_array($login_userid, $manager_userids);
        $show_btn = $is_manager || in_array($login_dept_id, array(6, 7, 11));
        $d_array['is_manager'] = $is_manager;
        $d_array['show_btn'] = $show_btn;
        if ($show_btn) {
            switch ($login_dept_id) {
                case 11:
                    $d_array['url'] = 'sale_soft';
                    break;
                case 7:
                case 6:
                    $d_array['url'] = 'sale';
                    break;
            }
        }
        echo_result($d_array);
    }
    if ($action == 4) {
        $user_token = new M_UserToken(request_login_userid());
        $user_token->set_query_fields(array('settings'));
        $db = create_pdo();
        $result = $user_token->load($db, $user_token);
        if (!$result[0]) {
            echo_result(array('code' => USER_ERROR, 'msg' => ''));
        }
        echo_result(array('code' => 0, 'data' => $user_token->to_array()));
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $talkData = request_object();
    $userid = 0;
    if (isset($talkData->userid)) {
        $userid = $talkData->userid;
    } else {
        $uid = request_userid();
        if (!isset($uid)) {
            die_error(USER_ERROR, "发送失败,请稍后重试~");
        }
        $userid = $uid;
    }
    //添加说说
    if ($action == 1) {
        $talk = new I_Talk();
        if (!isset($talkData->content)) {
            die_error(USER_ERROR, "内容不可以为空的哦~");
        } else {
            $talkData->content = str_replace("<div><br></div><div><br></div>", "", $talkData->content);
        }
        $employees = get_employees();
        $employee = $employees[$userid];
        $talk->set_userid($userid);
        $talk->set_username($employee['username']);
        $talk->set_addtime("now");
        $talk->set_field_from_array($talkData);
        $db = create_pdo();
        $result = $talk->insert($db, $talk);

        $user_token = new M_UserToken($userid);
        $user_token->load($db, $user_token);
        $talk_array = $talk->to_array();
        $talk_array['avatar'] = $user_token->get_avatar();
        $talk_array['exp'] = $user_token->get_exp();
        if (!$result[0]) die_error(USER_ERROR, "发送失败,请稍后重试~");
        echo_result(array('code' => 0, 'msg' => '发送成功~', 'talk' => $talk_array));
    }
    //回复
    if ($action == 2) {
        $comment = new I_Comment();
        if (!isset($talkData->content)) {
            die_error(USER_ERROR, "内容不可以为空的哦~");
        } else {
            $talkData->content = str_replace("<div><br></div><div><br></div>", "", $talkData->content);
        }
        $comment->set_field_from_array($talkData);
        $comment->set_addtime('now');
        $db = create_pdo();
        $result = $comment->insert($db, $comment);
        if (!$result[0]) die_error(USER_ERROR, "发送失败,请稍后重试~");
        $user_token = new M_UserToken($userid);
        $user_token->load($db, $user_token);
        $comment_array = $comment->to_array();
        $comment_array['avatar'] = $user_token->get_avatar();
        $comment_array['exp'] = $user_token->get_exp();
        echo_result(array('code' => 0, 'msg' => '发送成功~', 'comment' => $comment_array, 'current_date' => date('Y-m-d')));
    }
    //删除回复
    if ($action == 3) {
        $id = request_int("id");
        $comment = new I_Comment();
        $comment->set_where_and(I_Comment::$field_id, SqlOperator::Equals, $id);
        $comment->set_where_or(I_Comment::$field_comment_id, SqlOperator::Equals, $id);
        $db = create_pdo();
        $result = $comment->delete($db);
        if (!$result[0]) die_error(USER_ERROR, '删除评论失败~');
        echo_msg("删除评论成功~");
    }
    //删除动态 以及动态关联回复
    if ($action == 4) {
        $id = request_int("id");
        $talk = new I_Talk($id);
        $comment = new I_Comment();
        $comment->set_where_and(I_Comment::$field_talk_id, SqlOperator::Equals, $id);
        $db = create_pdo();
        pdo_transaction($db, function($db) use($talk, $comment) {
            $talk_result = $talk->delete($db);
            if (!$talk_result[0]) throw new TransactionException(PDO_ERROR_CODE, '删除动态失败。' . $talk_result['detail_cn'], $talk_result);
            $comment_result = $comment->delete($db, true);
            if (!$comment_result[0]) throw new TransactionException(PDO_ERROR_CODE, '删除动态失败。' . $comment_result['detail_cn'], $comment_result);
        });
        echo_msg("删除动态成功~");
    }

    if ($action == 5) {
        $settings = request_string("settings");
        $user_token = new M_UserToken(request_login_userid());
        $user_token->set_settings($settings);
        $db = create_pdo();
        $result = $user_token->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, "保存个人设置失败~");
        echo_msg("保存个人设置成功~");
    }
});
