<?php

/**
 * 食堂统计
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/5/12
 */
use Models\Base\Model;
use Models\W_CanteenStatistics;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';
require '../../common/http.php';
require '../../common/SysKV.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    $userid = request_userid();
    $searchTime = request_string('searchTime');
    $search_dept_id = request_int("dept_id");
    $depts = get_depts();
    $users = get_employees();
    $user = $users[$userid];
    $dept_id = $user['dept1_id'];
    $dept_name = "";
    if (!isset($searchTime)) {
        $searchTime = date('Y-m');
    }
    if ($action == 1) {
        $workTable = new W_CanteenStatistics();
        $sql = "SELECT mu.userid,wt.id,wt.m1,wt.m2,wt.m3,wt.m4,wt.m5,wt.m6,wt.m7,wt.m8,wt.m9,wt.m10,wt.m11,wt.m12,wt.m13,wt.m14,wt.m15,wt.m16,wt.m17,wt.m18,wt.m19,wt.m20,wt.m21,wt.m22,wt.m23,wt.m24,wt.m25,wt.m26,wt.m27,wt.m28,wt.m29,wt.m30,wt.m31,wt.remark,wt.date ";
        $sql.= "FROM M_User mu  LEFT JOIN (SELECT * FROM W_CanteenStatistics wt WHERE DATE_FORMAT(wt.date,'%Y-%m') = DATE_FORMAT('" . $searchTime . "','%Y-%m')) wt on mu.userid = wt.userid WHERE mu.username != 'admin' AND mu.`status` IN (1,2) AND mu.employee_no LIKE 'YC%' ";
        if (in_array($dept_id, array(1, 2))) {
            if (isset($search_dept_id)) {
                $sql.="AND mu.dept1_id = " . $search_dept_id . " ";
                $dept_name = $depts[$search_dept_id]['text'];
            } else {
                $dept_name = "全公司";
            }
        } else {
            if (!isset($search_dept_id)) {
                $sql.="AND mu.dept1_id = " . $dept_id . " ";
                $dept_name = $depts[$dept_id]['text'];
            } else {
                $sql .= "AND mu.dept1_id = " . $search_dept_id . " ";
                $dept_name = $depts[$search_dept_id]['text'];
            }
        }
        $sql .= "ORDER BY wt.id DESC";
        $db = create_pdo();
        $result = Model::query_list($db, $workTable, $sql);
        if (!$result[0]) die_error(USER_ERROR, '获取部门人员食堂统计列表信息失败~');
        $count_array = array('m1' => '0/0', 'm2' => '0/0', 'm3' => '0/0', 'm4' => '0/0', 'm5' => '0/0', 'm6' => '0/0', 'm7' => '0/0', 'm8' => '0/0', 'm9' => '0/0', 'm10' => '0/0', 'm11' => '0/0', 'm12' => '0/0', 'm13' => '0/0', 'm14' => '0/0', 'm15' => '0/0', 'm16' => '0/0', 'm17' => '0/0', 'm18' => '0/0', 'm19' => '0/0', 'm20' => '0/0', 'm21' => '0/0', 'm22' => '0/0', 'm23' => '0/0', 'm24' => '0/0', 'm25' => '0/0', 'm26' => '0/0', 'm27' => '0/0', 'm28' => '0/0', 'm29' => '0/0', 'm30' => '0/0', 'm31' => '0/0', 'count' => '0/0', 'name' => "统计");
        $models = Model::list_to_array($result['models'], array(), function (&$d) use($users, &$count_array) {
                    $model_n = 0;
                    $model_a = 0;
                    $d['name'] = $users[$d['userid']]['username'];
                    for ($i = 1; $i <= 31; $i++) {
                        $val = $d["m" . $i];
                        $array_count = $count_array["m" . $i];
                        $count_n = (int) explode("/", $array_count)[0];
                        $count_a = (int) explode("/", $array_count)[1];
                        $array_max_count = $count_array["count"];
                        $count_max_n = (int) explode("/", $array_max_count)[0];
                        $count_max_a = (int) explode("/", $array_max_count)[1];
                        if ($val == "N") {
                            $model_n ++;
                            $count_array["m" . $i] = ( ++$count_n) . "/" . $count_a;
                            $count_array["count"] = ( ++$count_max_n) . "/" . $count_max_a;
                        } else if ($val == "A") {
                            $model_a ++;
                            $count_array["m" . $i] = $count_n . "/" . ( ++$count_a);
                            $count_array["count"] = $count_max_n . "/" . ( ++$count_max_a);
                        } else if ($val == "N/A") {
                            $model_n ++;
                            $model_a ++;
                            $count_array["m" . $i] = ( ++$count_n) . "/" . ( ++$count_a);
                            $count_array["count"] = ( ++$count_max_n) . "/" . ( ++$count_max_a);
                        }
                    }
                    $d['count'] = $model_n . "/" . $model_a;
                });
        $models[count($models)] = $count_array;
        $returnTime = explode("-", $searchTime);
        $title = $returnTime[0] . "年" . $returnTime[1] . "月" . $dept_name . '食堂统计表';
        $return_array = array('currentDate' => $searchTime, 'c_d' => date('Y-m-d'), 'currentWeek' => Date("w", strtotime($searchTime)), 'start_year' => START_YEAR, 'current_year' => date('Y'), 'list' => $models, 'title' => $title);
        $return_array['can_write'] = SysKV::getValueByKey('write_canteen_statistics')['value'];
        echo_result($return_array);
    }
    if ($action == 2) {
        $work = new W_CanteenStatistics();
        $work->set_where_and(W_CanteenStatistics::$field_userid, SqlOperator::Equals, $userid);
        $work->set_custom_where(" AND DATE_FORMAT(date,'%Y-%m') = '" . date('Y-m') . "'");
        $db = create_pdo();
        $result = $work->load($db, $work);
        if ($result[0]) {
            echo_result(array('list' => $work->to_array(), 'current_date' => date('Y-m-d')));
        } else {
            echo_result(array('list' => NULL, 'current_date' => date('Y-m-d')));
        }
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    /**
     * 月度保存
     */
    if ($action == 1) {
        $workData = request_object();
        $work_has = new W_CanteenStatistics();
        $date = date('Y-m');
        if (isset($workData->add_date)) $date = date($workData->add_date);
        $work_has->set_where(W_CanteenStatistics::$field_userid, SqlOperator::Equals, $workData->userid);
        $work_has->set_custom_where(" AND DATE_FORMAT(date,'%Y-%m') = DATE_FORMAT('" . $date . "','%Y-%m') ");
        $db = create_pdo();
        $result = $work_has->load($db, $work_has);
        if ($result[0]) die_error(USER_ERROR, '本月食堂统计数据已提交,无需重复提交~');
        $work = new W_CanteenStatistics();
        $work->set_field_from_array($workData);
        $work->set_date($date);
        $result = $work->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '保存食堂统计数据失败~');
        echo_msg('保存食堂统计数据成功~ ');
    }
    if ($action == 2) {
        $ky = SysKV::getValueByKey("write_canteen_statistics");
        if ($ky['value'] == '1') {
            $workData = request_object();
            $work_has = new W_CanteenStatistics();
            $work_has->set_where(W_CanteenStatistics::$field_userid, SqlOperator::Equals, $workData->userid);
            $work_has->set_custom_where(" AND DATE_FORMAT(date,'%Y-%m') = '" . date('Y-m') . "'");
            $field_name = "m" . ((int) date('d'));
            $db = create_pdo();
            $result = $work_has->load($db, $work_has);
            if ($result[0]) {
                $field = $work_has->get_field_by_name($field_name);
                $value = $work_has->get_field_value($field);
                if (str_length($value) > 0) {
                    die_error(USER_ERROR, '当天食堂统计数据已提交,无需重复提交~');
                } else {
                    $work = new W_CanteenStatistics();
                    $work->set_where_and(W_CanteenStatistics::$field_userid, SqlOperator::Equals, $workData->userid);
                    $work->set_custom_where(" AND DATE_FORMAT(date,'%Y-%m') = '" . date('Y-m') . "'");
                    $work->set_date(date('Y-m-d'));
                    $work->set_field_value($work->get_field_by_name($field_name), $workData->eat_type);
                    $db = create_pdo();
                    $result = $work->update($db, true);
                    if (!$result[0]) die_error(USER_ERROR, '用餐统计数据保存失败~');
                    echo_msg('用餐统计数据保存成功~ ');
                }
            } else {
                $work = new W_CanteenStatistics();
                $work->set_userid($workData->userid);
                $work->set_dept_id($workData->dept_id);
                $work->set_date(date('Y-m-d'));
                $work->set_field_value($work->get_field_by_name($field_name), $workData->eat_type);
                $db = create_pdo();
                $result = $work->insert($db);
                if (!$result[0]) die_error(USER_ERROR, '用餐统计数据保存失败~');
                echo_msg('用餐统计数据保存成功~ ');
            }
        }else {
            die_error(USER_ERROR, '管理员还未开启用餐统计数据保存功能,请稍后~');
        }
    }
});
