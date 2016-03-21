<?php

/**
 * 用餐签出,签到
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/11/16
 */
use Models\Base\Model;
use Models\w_mealstable;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

list($action, $userid, $time, $sign) = filter_request(array(
    request_action(),
    request_int("userid"),
    request_datetime('time'),
    request_md5_32('sign')));

$sign_str = $time . $action . $userid . PUBLIC_KEY;
if (!str_equals($sign, md5($sign_str))) die_error(USER_ERROR, 'Error Signature');

execute_request(HttpRequestMethod::Post, function() use($action, $userid) {
    /**
     * 出去吃饭保存
     */
    if ($action == 1) {
        $meals = new w_mealstable();
        $users = get_employees();
        $user = $users[$userid];
        $c_date = date('Y-m-d');
        $time = time();
        $meals->set_custom_where(" AND DATE_FORMAT(add_date,'%Y-%m-%d') = '" . date('Y-m-d') . "' ");
        $meals->set_where_and(w_mealstable::$field_userid, SqlOperator::Equals, $userid);
        $meals->set_limit_count(1);
        $db = create_pdo();
        $exists_result = Model::query_list($db, $meals);
        if (!$exists_result[0]) die_error(USER_ERROR, "用餐签出失败,请稍等重试~");
        $sa_time = strtotime($c_date . " 11:30:00");
        $ea_time = strtotime($c_date . " 13:00:00");
        $sn_time = strtotime($c_date . " 17:30:00");
        $en_time = strtotime($c_date . " 18:40:00");
        if ($exists_result['count'] === 0) {
            $meals->reset();
            $meals->set_dept_id($user['dept1_id']);
            $meals->set_userid($userid);
            $meals->set_add_date(date("Y-m-d"));
            if ($time >= $sa_time && $time <= $ea_time) {
                $meals->set_ma_out(date('Y-m-d H:i:s'));
            } else if ($time >= $sn_time && $time <= $en_time) {
                $meals->set_mn_out(date('Y-m-d H:i:s'));
            } else {
                die_error(USER_ERROR, "还未到饭点,暂时不可签出~");
            }
            $ins_result = $meals->insert($db);
            if (!$ins_result[0]) die_error(USER_ERROR, "用餐签出失败,请稍等重试~");
        }else {
            $meals_r = $exists_result['models'][0];
            $meals_array = $meals_r->to_array();
            $meals->reset();
            $meals->set_field_from_array($meals_array);
            if ($time >= $sa_time && $time <= $ea_time) {
                $ma_out = $meals->get_ma_out();
                if ($ma_out !== NULL) die_error(USER_ERROR, "用餐已签出,请不要重复签出~");
                $meals->set_ma_out(date('Y-m-d H:i:s'));
            } else if ($time >= $sn_time && $time <= $en_time) {
                $mn_out = $meals->get_mn_out();
                if ($mn_out !== NULL) die_error(USER_ERROR, "用餐已签出,请不要重复签出~");
                $meals->set_mn_out(date('Y-m-d H:i:s'));
            } else {
                die_error(USER_ERROR, "还未到饭点,暂时不可签出~");
            }
            $up_result = $meals->update($db);
            if (!$up_result[0]) die_error(USER_ERROR, "用餐签出失败,请稍等重试~");
        }
        echo_msg("用餐签出成功~");
    }
    /**
     * 吃饭回来保存
     */
    if ($action == 2) {
        $meals = new w_mealstable();
        $users = get_employees();
        $user = $users[$userid];
        $c_date = date('Y-m-d');
        $time = time();
        $meals->set_custom_where(" AND DATE_FORMAT(add_date,'%Y-%m-%d') = '" . date('Y-m-d') . "' ");
        $meals->set_where_and(w_mealstable::$field_userid, SqlOperator::Equals, $userid);
        $meals->set_limit_count(1);
        $db = create_pdo();
        $exists_result = Model::query_list($db, $meals);
        if (!$exists_result[0]) die_error(USER_ERROR, "用餐签出失败,请稍等重试~");
        $sa_time = strtotime($c_date . " 11:30:00");
        $ea_time = strtotime($c_date . " 13:00:00");
        $sn_time = strtotime($c_date . " 17:30:00");
        $en_time = strtotime($c_date . " 18:40:00");        
        if ($exists_result['count'] === 0) {
            die_error(USER_ERROR, "签到失败,还未签出~");
        } else {
            $meals_r = $exists_result['models'][0];
            $meals_array = $meals_r->to_array();
            $meals->reset();
            $meals->set_field_from_array($meals_array);
            if ($time >= $sa_time && $time <= $ea_time) {
                $ma_out = $meals->get_ma_out();
                if ($ma_out === NULL) die_error(USER_ERROR, "签到失败,还未签出~");
                $ma_in = $meals->get_ma_in();
                if ($ma_in !== NULL) die_error(USER_ERROR, "用餐已签到,请不要重复签到~");
                $meals->set_ma_in(date('Y-m-d H:i:s'));
            } else if ($time >= $sn_time && $time <= $en_time) {
                $mn_out = $meals->get_mn_out();
                if ($mn_out === NULL) die_error(USER_ERROR, "签到失败,还未签出~");
                $mn_in = $meals->get_mn_in();
                if ($mn_in !== NULL) die_error(USER_ERROR, "用餐已签到,请不要重复签到~");
                $meals->set_mn_in(date('Y-m-d H:i:s'));
            } else {
                die_error(USER_ERROR, "还未到饭点,暂时不可签出~");
            }
            $up_result = $meals->update($db);
            if (!$up_result[0]) die_error(USER_ERROR, "用餐签出失败,请稍等重试~");
        }
        echo_msg("用餐签到成功~");
    }
});
