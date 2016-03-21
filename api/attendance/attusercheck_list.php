<?php

/*
 * 考勤用户
 */

use Models\Base\Model;
use Models\Base\SqlSortType;
use Models\v_userchecklist;
use Models\W_WorkTable;
use Models\A_CauseLeave;
use Models\A_AdjustRest;
use Models\w_classes;
use Models\Base\SqlOperator;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../common/http.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $month = request_string('month');
        $dept = request_int('dept');
        $userchecklist = new v_userchecklist();
        if (!isset($month)) {
            $month = date("m");
        }
        $sql = "select v.* from v_userchecklist v INNER JOIN m_user m ON v.userid = m.userid WHERE m.`status` IN (1,2) AND v.userid != 1 ";
        $sql.="AND DATE_FORMAT(v.dy,'%Y-%m') = '" . date("Y") . "-" . $month . "' ";
        if (isset($dept)) {
            $sql.="AND v.dept_id = " . $dept . " ";
        }
        if (isset($sort) && isset($sortname)) {
            $sql.="ORDER BY v." . $sortname . " " . $sort . " ";
        } else {
            $sql.="ORDER BY v.userid ASC ";
        }
        $db = create_pdo();
        $sql_result = Model::execute_custom_sql($db, $sql);
        $result_total_count = $sql_result['count'];
        $sql .= "LIMIT " . request_pagesize() * (request_pageno() - 1) . "," . request_pagesize();
        $result = Model::query_list($db, $userchecklist, $sql);
        if (!$result[0]) die_error(USER_ERROR, '获取考勤用户信息失败，请重试');
        $employees = get_employees();
        $depts = get_depts();
        $userids_array = array();
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($employees, $depts, &$userids_array) {
                    $d['username'] = $employees[$d['userid']]['username'];
                    $d['deptname'] = $depts[$d['dept_id']]['text'];
                    for ($i = 1; $i <= 31; $i++) {
                        $m = $d['m' . $i];
                        $z = $d['z' . $i];
                        if (is_date($m)) {
                            $d['m' . $i] = explode(" ", $m)[1];
                        }
                        if (is_date($z)) {
                            $d['z' . $i] = explode(" ", $z)[1];
                        }
                    }
                    array_push($userids_array, $d['userid']);
                });
        //获取请假
        $date_format = date('Y') . "-" . $month;
        $causeLeave = new A_CauseLeave();
        $causeLeave->set_where_and(A_CauseLeave::$field_userid, SqlOperator::In, $userids_array);
        $causeLeave->set_where_and(A_CauseLeave::$field_status, SqlOperator::Equals, 6);
        $causeLeave->set_custom_where(" AND ( DATE_FORMAT(starttime,'%Y-%m') = '" . $date_format . "' OR DATE_FORMAT(endtime,'%Y-%m') = '" . $date_format . "') ");
        $causeLeave->set_query_fields(array('userid', 'starttime', 'endtime', 'hours', 'type'));
        $causeLeave_result = Model::query_list($db, $causeLeave);
        if (!$causeLeave_result[0]) die_error(USER_ERROR, '获取考勤用户信息失败，请重试');
        $causeLeave_array = array();
        Model::list_to_array($causeLeave_result['models'], array(), function ($d) use(&$causeLeave_array) {
            if (isset($causeLeave_array[$d['userid']])) {
                array_push($causeLeave_array[$d['userid']], array('userid' => $d['userid'], 'starttime' => $d['starttime'], 'endtime' => $d['endtime'], 'hours' => $d['hours'], 'type' => $d['type']));
            } else {
                $causeLeave_array[$d['userid']] = array(array('userid' => $d['userid'], 'starttime' => $d['starttime'], 'endtime' => $d['endtime'], 'hours' => $d['hours'], 'type' => $d['type']));
            }
        });
        //获取调休
        $adjustrest = new A_AdjustRest();
        $adjustrest->set_where_and(A_AdjustRest::$field_userid, SqlOperator::In, $userids_array);
        $adjustrest->set_where_and(A_CauseLeave::$field_status, SqlOperator::Equals, 6);
        $adjustrest->set_custom_where(" AND( DATE_FORMAT(adjust_to,'%Y-%m') = '" . $date_format . "' OR DATE_FORMAT(rest_date,'%Y-%m') = '" . $date_format . "') ");
        $adjustrest->set_query_fields(array('userid', 'rest_date', 'rest_days', 'adjust_to', 'adjust_days'));
        $adjustrest_result = Model::query_list($db, $adjustrest);
        if (!$adjustrest_result[0]) die_error(USER_ERROR, '获取考勤用户信息失败，请重试');
        $adjustrest_array = array();
        Model::list_to_array($adjustrest_result['models'], array(), function($d) use(&$adjustrest_array) {
            if (isset($adjustrest_array[$d['userid']])) {
                array_push($adjustrest_array[$d['userid']], array('userid' => $d['userid'], 'rest_date' => $d['rest_date'], 'rest_days' => $d['rest_days'], 'adjust_to' => $d['adjust_to'], 'adjust_days' => $d['adjust_days']));
            } else {
                $adjustrest_array[$d['userid']] = array(array('userid' => $d['userid'], 'rest_date' => $d['rest_date'], 'rest_days' => $d['rest_days'], 'adjust_to' => $d['adjust_to'], 'adjust_days' => $d['adjust_days']));
            }
        });
        array_walk($models, function(&$d) use ($causeLeave_array, $adjustrest_array, $date_format) {
            $causeLeaves = $causeLeave_array[$d['userid']]; //请假
            $adjustrests = $adjustrest_array[$d['userid']]; //调休
            if ($causeLeaves != NULL) {
                foreach ($causeLeaves as $causeLeave) {
                    $start_time = date_formart(date('Y-m-d H:i:s', strtotime($causeLeave['starttime'])));
                    $end_time = date_formart(date('Y-m-d H:i:s', strtotime($causeLeave['endtime'])));
                    $hours = $causeLeave['hours'];
                    if (($start_time['m'] == $end_time['m']) && ($start_time['d'] == $end_time['d']) && $hours <= 24) {
                        if ($start_time['h'] <= 12) {
                            $d['m' . $start_time['d']] .= "(假)";
                        }
                        if ($end_time['h'] > 12) {
                            $d['z' . $end_time['d']] .= "(假)";
                        }
                    } else {
                        $s = $start_time['d'];
                        $e = ($start_time['m'] == $end_time['m']) ? $end_time['d'] : 31;
                        for ($i = $s; $i < $e; $i++) {
                            $d['m' . $i] .= "(假)";
                            $d['z' . $i] .= "(假)";
                        }
                    }
                }
            }
            if ($adjustrests != NULL) {
                foreach ($adjustrests as $adjustrest) {
                    $rest_date = date_formart(date('Y-m-d H:i:s', strtotime($adjustrest['rest_date'])));
                    $adjust_to = date_formart(date('Y-m-d H:i:s', strtotime($adjustrest['adjust_to'])));
                    $current_date = date_formart(date('Y-m-d H:i:s', strtotime($date_format . "-01 00:00:00")));
                    if (($current_date['y'] == $rest_date['y']) && ($current_date['m'] == $rest_date['m'])) {
                        $d['m' . $rest_date['d']] .= "(调" . (($adjust_to['m'] == $current_date['m']) ? $adjust_to['d'] : ($adjust_to['m'] . '-' . $adjust_to['d'])) . ")";
                        $d['z' . $rest_date['d']] .= "(调" . (($adjust_to['m'] == $current_date['m']) ? $adjust_to['d'] : ($adjust_to['m'] . '-' . $adjust_to['d'])) . ")";
                    }
                    if (($current_date['y'] == $adjust_to['y']) && ($current_date['m'] == $adjust_to['m'])) {
                        $d['m' . $adjust_to['d']] .= "(调" . (($rest_date['m'] == $current_date['m']) ? $rest_date['d'] : ($rest_date['m'] . '-' . $rest_date['d'])) . ")";
                        $d['z' . $adjust_to['d']] .= "(调" . (($rest_date['m'] == $current_date['m']) ? $rest_date['d'] : ($rest_date['m'] . '-' . $rest_date['d'])) . ")";
                    }
                }
            }
        });
        echo_result(array('total_count' => $result_total_count, 'list' => $models, 'page_no' => request_pageno(), 'max_page_no' => ceil($result_total_count / request_pagesize()), 'current_day' => date('d'), 'current_month' => date('m')));
    }
    if ($action == 11) {
        $month = request_string('month');
        $dept_id = request_string('dept_id');
        if (isset($month)) {
            $month = date("Y") . "-" . $month;
        } else {
            $month = date("Y-m");
        }
        $userchecklist = new v_userchecklist();
        $export = new ExportData2Excel();
        $sql = "select v.* from v_userchecklist v INNER JOIN m_user m ON v.userid = m.userid WHERE m.`status` IN (1,2) AND v.userid != 1 ";
        $sql.="AND DATE_FORMAT(v.dy,'%Y-%m') = '" . $month . "' ";
        if (isset($dept_id)) {
            $sql.="AND v.dept_id = " . $dept_id . " ";
        }
        $db = create_pdo();
        $result = Model::query_list($db, $userchecklist, $sql, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('考勤检查数据导出失败,请稍后重试!')), "考勤检查数据导出", "考勤检查");
        }
        $employees = get_employees();
        $depts = get_depts();
        $userids_array = array();
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($employees, $depts, $userids_array) {
                    $d['username'] = $employees[$d['userid']]['username'];
                    $d['deptname'] = $depts[$d['dept_id']]['text'];
                    for ($i = 1; $i <= 31; $i++) {
                        $m = $d['m' . $i];
                        $z = $d['z' . $i];
                        if (is_date($m)) {
                            $d['m' . $i] = explode(" ", $m)[1];
                        }
                        if (is_date($z)) {
                            $d['z' . $i] = explode(" ", $z)[1];
                        }
                    }
                    array_push($userids_array, $d['userid']);
                });

        //获取请假
        $causeLeave = new A_CauseLeave();
        $causeLeave->set_where_and(A_CauseLeave::$field_userid, SqlOperator::In, $userids_array);
        $causeLeave->set_where_and(A_CauseLeave::$field_status, SqlOperator::Equals, 6);
        $causeLeave->set_custom_where(" AND ( DATE_FORMAT(starttime,'%Y-%m') = '" . $month . "' OR DATE_FORMAT(endtime,'%Y-%m') = '" . $month . "') ");
        $causeLeave->set_query_fields(array('userid', 'starttime', 'endtime', 'hours', 'type'));
        $causeLeave_result = Model::query_list($db, $causeLeave);
        if (!$causeLeave_result[0]) die_error(USER_ERROR, '获取考勤用户信息失败，请重试');
        $causeLeave_array = array();
        Model::list_to_array($causeLeave_result['models'], array(), function ($d) use(&$causeLeave_array) {
            if (isset($causeLeave_array[$d['userid']])) {
                array_push($causeLeave_array[$d['userid']], array('userid' => $d['userid'], 'starttime' => $d['starttime'], 'endtime' => $d['endtime'], 'hours' => $d['hours'], 'type' => $d['type']));
            } else {
                $causeLeave_array[$d['userid']] = array(array('userid' => $d['userid'], 'starttime' => $d['starttime'], 'endtime' => $d['endtime'], 'hours' => $d['hours'], 'type' => $d['type']));
            }
        });
        //获取调休
        $adjustrest = new A_AdjustRest();
        $adjustrest->set_where_and(A_AdjustRest::$field_userid, SqlOperator::In, $userids_array);
        $adjustrest->set_where_and(A_CauseLeave::$field_status, SqlOperator::Equals, 6);
        $adjustrest->set_custom_where(" AND( DATE_FORMAT(adjust_to,'%Y-%m') = '" . $month . "' OR DATE_FORMAT(rest_date,'%Y-%m') = '" . $month . "') ");
        $adjustrest->set_query_fields(array('userid', 'rest_date', 'rest_days', 'adjust_to', 'adjust_days'));
        $adjustrest_result = Model::query_list($db, $adjustrest);
        if (!$adjustrest_result[0]) die_error(USER_ERROR, '获取考勤用户信息失败，请重试');
        $adjustrest_array = array();
        Model::list_to_array($adjustrest_result['models'], array(), function($d) use(&$adjustrest_array) {
            if (isset($adjustrest_array[$d['userid']])) {
                array_push($adjustrest_array[$d['userid']], array('userid' => $d['userid'], 'rest_date' => $d['rest_date'], 'rest_days' => $d['rest_days'], 'adjust_to' => $d['adjust_to'], 'adjust_days' => $d['adjust_days']));
            } else {
                $adjustrest_array[$d['userid']] = array(array('userid' => $d['userid'], 'rest_date' => $d['rest_date'], 'rest_days' => $d['rest_days'], 'adjust_to' => $d['adjust_to'], 'adjust_days' => $d['adjust_days']));
            }
        });
        array_walk($models, function(&$d) use ($causeLeave_array, $adjustrest_array, $month) {
            $causeLeaves = $causeLeave_array[$d['userid']]; //请假
            $adjustrests = $adjustrest_array[$d['userid']]; //调休
            if ($causeLeaves != NULL) {
                foreach ($causeLeaves as $causeLeave) {
                    $start_time = date_formart(date('Y-m-d H:i:s', strtotime($causeLeave['starttime'])));
                    $end_time = date_formart(date('Y-m-d H:i:s', strtotime($causeLeave['endtime'])));
                    $hours = $causeLeave['hours'];
                    if (($start_time['m'] == $end_time['m']) && ($start_time['d'] == $end_time['d']) && $hours <= 24) {
                        if ($start_time['h'] <= 12) {
                            $d['m' . $start_time['d']] .= "(假)";
                        }
                        if ($end_time['h'] > 12) {
                            $d['z' . $end_time['d']] .= "(假)";
                        }
                    } else {
                        $s = $start_time['d'];
                        $e = ($start_time['m'] == $end_time['m']) ? $end_time['d'] : 31;
                        for ($i = $s; $i < $e; $i++) {
                            $d['m' . $i] .= "(假)";
                            $d['z' . $i] .= "(假)";
                        }
                    }
                }
            }
            if ($adjustrests != NULL) {
                foreach ($adjustrests as $adjustrest) {
                    $rest_date = date_formart(date('Y-m-d H:i:s', strtotime($adjustrest['rest_date'])));
                    $adjust_to = date_formart(date('Y-m-d H:i:s', strtotime($adjustrest['adjust_to'])));
                    $current_date = date_formart(date('Y-m-d H:i:s', strtotime($month . "-01 00:00:00")));
                    if (($current_date['y'] == $rest_date['y']) && ($current_date['m'] == $rest_date['m'])) {
                        $d['m' . $rest_date['d']] .= "(调" . (($adjust_to['m'] == $current_date['m']) ? $adjust_to['d'] : ($adjust_to['m'] . '-' . $adjust_to['d'])) . ")";
                        $d['z' . $rest_date['d']] .= "(调" . (($adjust_to['m'] == $current_date['m']) ? $adjust_to['d'] : ($adjust_to['m'] . '-' . $adjust_to['d'])) . ")";
                    }
                    if (($current_date['y'] == $adjust_to['y']) && ($current_date['m'] == $adjust_to['m'])) {
                        $d['m' . $adjust_to['d']] .= "(调" . (($rest_date['m'] == $current_date['m']) ? $rest_date['d'] : ($rest_date['m'] . '-' . $rest_date['d'])) . ")";
                        $d['z' . $adjust_to['d']] .= "(调" . (($rest_date['m'] == $current_date['m']) ? $rest_date['d'] : ($rest_date['m'] . '-' . $rest_date['d'])) . ")";
                    }
                }
            }
        });
        $field = array("dy", 'username', 'deptname');
        $th = array('日期', '用户', '部门');
        $field_width = array(11, 8, 12);
        for ($i = 1; $i <= 31; $i++) {
            array_push($field, 'm' . $i);
            array_push($field, 'z' . $i);
            array_push($th, $i . '号上午');
            array_push($th, $i . '号下午');
            array_push($field_width, 13);
            array_push($field_width, 13);
        }
        $export->set_field($field);
        $export->set_field_width($field_width);
        $export->create($th, $models, explode('-', $month)[1] . '月考' . get_depts()[$dept_id]['text'] . '勤检查数据导出', '考勤检查');
    }
    if ($action == 12) {
        $days = request_string("days");
        if (!isset($days)) {
            $days = date("d");
        }
        $export = new ExportData2Excel();
        $sql = "SELECT v.dy,v.userid,m.username,d.text AS deptname,v.m" . $days . ",v.z" . $days . " FROM v_userchecklist v ";
        $sql .= "INNER JOIN m_user m ON v.userid = m.userid ";
        $sql .= "INNER JOIN m_dept d ON v.dept_id = d.id ";
        $sql .= "WHERE m.`status` IN (1,2) AND d.id != 1 AND m.userid != 1 AND m.username NOT LIKE '%测试用户%' AND DATE_FORMAT(dy,'%Y-%m') = '" . date('Y-m') . "' ";
        $sql .= "AND (v.m" . $days . " NOT IN ('Y','休') OR v.z" . $days . " NOT IN ('Y','休')) ";
        $sql .="ORDER BY v.dept_id ASC ";
        $db = create_pdo();
        $result = Model::execute_custom_sql($db, $sql);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('考勤异常据导出失败,请稍后重试!')), "考勤异常数据导出", "考勤异常");
        }
        $att_array = array();
        //考勤异常人员ID
        $userids_array = array();
        array_walk($result['results'], function ($d) use ($days, &$att_array, &$userids_array) {
            array_push($att_array, array('deptname' => $d['deptname'], 'userid' => $d['userid'], 'username' => $d['username'], 'm' . $days => $d['m' . $days], 'z' . $days => $d['z' . $days]));
            array_push($userids_array, $d['userid']);
        });
        //获取班次
        $classz = new w_classes();
        $classz_result = Model::query_list($db, $classz);
        if (!$classz_result[0]) {
            $export->create(array('导出错误'), array(array('考勤异常据导出失败,请稍后重试!')), "考勤异常数据导出", "考勤异常");
        }
        $classz_array = array();
        Model::list_to_array($classz_result['models'], array(), function($d) use(&$classz_array) {
            $classz_array[$d['cid']] = $d;
        });
        //获取排班
        $worktable = new W_WorkTable();
        $worktable->set_where_and(W_WorkTable::$field_userid, SqlOperator::In, $userids_array);
        $worktable->set_custom_where(" AND DATE_FORMAT(date,'%Y-%m-%d') = '" . date('Y-m-01') . "' ");
        $worktable_result = Model::query_list($db, $worktable);
        if (!$worktable_result[0]) {
            $export->create(array('导出错误'), array(array('考勤异常据导出失败,请稍后重试!')), "考勤异常数据导出", "考勤异常");
        }
        $worktable_array = array();
        Model::list_to_array($worktable_result['models'], array(), function($d) use(&$worktable_array) {
            $worktable_array[$d['userid']] = $d;
        });
        //获取请假
        $date_format = date('Y-m-' . $days);
        $causeLeave = new A_CauseLeave();
        $causeLeave->set_where_and(A_CauseLeave::$field_userid, SqlOperator::In, $userids_array);
        $causeLeave->set_where_and(A_CauseLeave::$field_status, SqlOperator::Equals, 6);
        $causeLeave->set_custom_where(" AND DATE_FORMAT(starttime,'%Y-%m-%d') <= '" . $date_format . "' AND DATE_FORMAT(endtime,'%Y-%m-%d') >= '" . $date_format . "' ");
        $causeLeave->set_query_fields(array('userid', 'starttime', 'endtime', 'type'));
        $causeLeave_result = Model::query_list($db, $causeLeave);
        if (!$causeLeave_result[0]) {
            $export->create(array('导出错误'), array(array('考勤异常据导出失败,请稍后重试!')), "考勤异常数据导出", "考勤异常");
        }
        $causeLeave_array = array();
        Model::list_to_array($causeLeave_result['models'], array(), function ($d) use(&$causeLeave_array) {
            $causeLeave_array[$d['userid']] = array('userid' => $d['userid'], 'starttime' => $d['starttime'], 'endtime' => $d['endtime'], 'type' => $d['type']);
        });
        //获取调休
        $adjustrest = new A_AdjustRest();
        $adjustrest->set_where_and(A_AdjustRest::$field_userid, SqlOperator::In, $userids_array);
        $adjustrest->set_where_and(A_CauseLeave::$field_status, SqlOperator::Equals, 6);
        $adjustrest->set_custom_where(" AND DATE_FORMAT(adjust_to,'%Y-%m-%d') = '" . $date_format . "' ");
        $adjustrest->set_query_fields(array('userid', 'rest_date', 'rest_days', 'adjust_to', 'adjust_days'));
        $adjustrest_result = Model::query_list($db, $adjustrest);
        if (!$adjustrest_result[0]) {
            $export->create(array('导出错误'), array(array('考勤异常据导出失败,请稍后重试!')), "考勤异常数据导出", "考勤异常");
        }
        $adjustrest_array = array();
        Model::list_to_array($adjustrest_result['models'], array(), function($d) use(&$adjustrest_array) {
            $adjustrest_array[$d['userid']] = array('userid' => $d['userid'], 'rest_date' => $d['rest_date'], 'rest_days' => $d['rest_days'], 'adjust_to' => $d['adjust_to'], 'adjust_days' => $d['adjust_days']);
        });

        array_walk($att_array, function(&$d) use($classz_array, $worktable_array, $causeLeave_array, $adjustrest_array, $date_format, $days) {
            foreach ($d as $key => $value) {
                if (str_equals($key, 'm' . $days) || str_equals($key, 'z' . $days)) {
                    if (is_date($d[$key])) {
                        $work_ = $worktable_array[$d['userid']]['m' . $days];
                        $classz = $classz_array[$work_];
                        if (str_equals($key, 'm' . $days)) {
                            $time = (str_length($classz['stime']) === 3) ? str_replace(substr($classz['stime'], 0, 1), '0' . substr($classz['stime'], 0, 1) . ':', $classz['stime']) : str_replace(substr($classz['stime'], 0, 2), substr($classz['stime'], 0, 2) . ':', $classz['stime']);
                            $time = explode(" ", $d[$key])[0] . " " . $time . ":00";
                            $str = get_date_time_str($time, $d[$key]);
                            $d['remark'] .= "迟到" . $str . "  ";
                        } else {
                            $time = (str_length($classz['etime']) === 3) ? str_replace(substr($classz['etime'], 0, 1), '0', substr($classz['etime'], 0, 1) . ':', $classz['etime']) : str_replace(substr($classz['etime'], 0, 2), substr($classz['etime'], 0, 2) . ':', $classz['etime']);
                            $time = explode(" ", $d[$key])[0] . " " . $time . ":00";
                            $str = get_date_time_str($d[$key], $time);
                            $d['remark'] .= "早退" . $str . "  ";
                        }
                    } else {
                        $causeLeave = $causeLeave_array[$d['userid']];
                        $adjustrest = $adjustrest_array[$d['userid']];
                        if (str_equals($d[$key], '无打卡')) {
                            if ($causeLeave == NULL && $adjustrest == NULL) {
                                if (str_equals($key, 'm' . $days)) {
                                    $d['remark'] .="上班未打卡  ";
                                } else {
                                    $d['remark'] .="下班未打卡  ";
                                }
                            } else {
                                if ($causeLeave != NULL) {
                                    $work_ = $worktable_array[$d['userid']]['m' . $days];
                                    $classz = $classz_array[$work_];
                                    if (str_equals($key, 'm' . $days)) {
                                        $time = (str_length($classz['stime']) === 3) ? str_replace(substr($classz['stime'], 0, 1), '0' . substr($classz['stime'], 0, 1) . ':', $classz['stime']) : str_replace(substr($classz['stime'], 0, 2), substr($classz['stime'], 0, 2) . ':', $classz['stime']);
                                        $time = $time . ":00";
                                        $check_date = $date_format . ' ' . $time;
                                        if (is_in2date($causeLeave['starttime'], $causeLeave['endtime'], $check_date)) {
                                            $d['remark'] .="上班" . get_causeLeave_type($causeLeave['type']) . "  ";
                                        } else {
                                            $d['remark'].= "上班未打卡  ";
                                        }
                                    } else {
                                        $time = (str_length($classz['etime']) === 3) ? str_replace(substr($classz['etime'], 0, 1), '0', substr($classz['etime'], 0, 1) . ':', $classz['etime']) : str_replace(substr($classz['etime'], 0, 2), substr($classz['etime'], 0, 2) . ':', $classz['etime']);
                                        $time = $time . ":00";
                                        $check_date = $date_format . ' ' . $time;
                                        if (is_in2date($causeLeave['starttime'], $causeLeave['endtime'], $check_date)) {
                                            $d['remark'] .="下班" . get_causeLeave_type($causeLeave['type']) . "  ";
                                        } else {
                                            $d['remark'].= "下班未打卡  ";
                                        }
                                    }
                                }
                                if ($adjustrest != NULL) {
                                    $adjust_days = $adjustrest['adjust_days']; //调休天数
                                    if ($adjust_days == 1) {
                                        $d['remark'] = "全天调休  ";
                                    } else if ($adjust_days == 0.5) {
                                        if (str_equals($d['m' . $days], "无打卡") && str_equals($d['z' . $days], "无打卡")) {
                                            $d['remark'] = "调休0.5天上下班无打卡  ";
                                        } else {
                                            if (str_equals($d['m' . $days], "无打卡")) {
                                                $d['remark'] = "下午调休";
                                            } else {
                                                $d['remark'] = "上午调休";
                                            }
                                        }
                                    }
                                }
                            }
                        } else if (str_equals($d[$key], '无排班')) {
                            $d['remark'] = "无排班  ";
                        }
                    }
                }
            }
        });
        $export->set_field(array('deptname', 'username', 'remark'));
        $export->set_field_width(array(15, 15, 40));
        $export->set_row_height(20);
        $export->create(array('部门', '姓名', '异常情况'), $att_array, $days . "号考勤异常导出", "考勤异常");
    }
});

/**
 * 
 * @param type $time_str
 */
function date_formart($time_str) {
    $dates = explode(" ", $time_str);
    $date = array();
    $d = explode("-", $dates[0]);
    $date['y'] = (int) $d[0];
    $date['m'] = (int) $d[1];
    $date['d'] = (int) $d[2];
    $d = explode(":", $dates[1]);
    $date['h'] = (int) $d[0];
    $date['i'] = (int) $d[1];
    $date['s'] = (int) $d[2];
    return $date;
}

/**
 * 判断是否是时间
 * @param type $date
 * @return boolean
 */
function is_date($date) {
    if ($date == date('Y-m-d H:i:s', strtotime($date))) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/**
 * 判断 $date 是否在 $sdate 与 $edate 之间
 * @param type $sdate
 * @param type $edate
 * @param type $date
 * @return boolean
 */
function is_in2date($sdate, $edate, $date) {
    $sdate = strtotime($sdate);
    $edate = strtotime($edate);
    $date = strtotime($date);
    if ($sdate <= $date && $date <= $edate) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/**
 * 获取假别
 * @param type $type_id
 * @return string
 */
function get_causeLeave_type($type_id) {
    $array = array('1' => '事假', '2' => '病假', '3' => '产假', '4' => '丧假', '5' => '婚假', '6' => '陪产假');
    return $array[$type_id];
}

/**
 * 计算两个时间差
 * @param type $startdate
 * @param type $enddate
 * @return string
 */
function get_date_time_str($startdate, $enddate) {
    $date_num = strtotime($enddate) - strtotime($startdate);
    $date = floor($date_num / 86400);
    $hour = floor(($date_num % 86400) / 3600);
    $minute = floor((($date_num % 86400) % 3600) / 60);
    $second = floor((($date_num % 86400) % 3600) % 60);
    $res_str = "";
    if ($date > 0) {
        $res_str.= $date . "天";
    }
    if ($hour > 0) {
        $res_str.= $hour . "小时";
    }
    if ($minute > 0) {
        $res_str.= $minute . "分钟";
    }
    if ($second > 0) {
        $res_str.= $second . "秒";
    }
    return $res_str;
}
