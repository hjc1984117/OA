<?php

/**
 * QQ接入
 */
use Models\Base\Model;
use Models\p_qqaccess_soft;
use Models\p_qqreception_soft;
use Models\p_channel_site_soft;
use Models\M_User;
use Models\Base\SqlSortType;
use Models\Base\SqlOperator;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require '../../api/sale/update_salestatistics.php';
require_once '../../common/http.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if (!isset($action)) $action = -1;
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $searchName = request_string('searchName');
    $searchTime = request_string('searchTime');
    $searchChannel = request_string('searchChannel');
    $searchStatus = request_string('searchStatus');
    if (!isset($searchTime) && !request_string('searchName')) {
        $searchTime = date("Y-m-d");
        if (date("H") < 3) {
            $searchTime = date("Y-m-d", strtotime("-1 day"));
        }
    }
    if ($action == 1) {
        $qq_access = new p_qqaccess_soft();
        $where_sql = '';
        if (isset($searchName)) {
            $qq_access->set_custom_where(" AND (into_url LIKE '%" . $searchName . "%' OR add_username LIKE '%" . $searchName . "%' OR qq_num LIKE '%" . $searchName . "%' OR customer_num LIKE '%" . $searchName . "%' OR customer_address LIKE '%" . $searchName . "%' OR keyword LIKE '%" . $searchName . "%' OR presales LIKE '%" . $searchName . "%' )");
            $where_sql.= " AND (into_url LIKE '%" . $searchName . "%' OR add_username LIKE '%" . $searchName . "%' OR qq_num LIKE '%" . $searchName . "%' OR customer_num LIKE '%" . $searchName . "%' OR customer_address LIKE '%" . $searchName . "%' OR keyword LIKE '%" . $searchName . "%' OR presales LIKE '%" . $searchName . "%' )";
        }
        if (isset($searchTime)) {
            $searchStartTime = date("Y-m-d 03:00:00", strtotime($searchTime));
            $searchEndTime = date("Y-m-d 03:00:00", strtotime('+1 day', strtotime($searchTime)));
            $qq_access->set_custom_where(" AND DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') >= '" . $searchStartTime . "' ");
            $qq_access->set_custom_where(" AND DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') <= '" . $searchEndTime . "' ");         
            $where_sql.= " AND DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') >= '" . $searchStartTime . "' ";
            $where_sql.= " AND DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') <= '" . $searchEndTime . "' ";
        }
        if (isset($searchChannel)) {
            $qq_access->set_where_and(p_qqaccess_soft::$field_channel, SqlOperator::Equals, $searchChannel);
            $where_sql.=" AND channel = '" . $searchChannel . "'";
        }
        if (isset($searchStatus)) {
            switch ($searchStatus) {
                case '已接入':
                default:
                    $qq_access->set_custom_where(" AND NOT ISNULL(access_time ) ");
                    $where_sql.=" AND NOT ISNULL(access_time ) ";
                    break;
                case '未接入':
                    $qq_access->set_custom_where(" AND ISNULL(access_time ) ");
                    $where_sql.=" AND ISNULL(access_time ) ";
                    break;
                case '已验证':
                    $qq_access->set_where_and(p_qqaccess_soft::$field_hasValidation, SqlOperator::Equals, 2);
                    $where_sql.=" AND hasValidation = 2 ";
                    break;
                case '待验证':
                    $qq_access->set_where_and(p_qqaccess_soft::$field_hasValidation, SqlOperator::Equals, 1);
                    $where_sql.=" AND hasValidation = 1 ";
                    break;
            }
        }
        $login_userid = request_login_userid();
        $employees = get_employees();
        $dept_id = $employees[$login_userid]['dept1_id'];
        $is_manager = is_manager($login_userid, 2);
        $role_id = get_role_id($login_userid);
        $is_swt = in_array($role_id, get_zoosnet_role_ids());
        $is_jjb = in_array($dept_id, array(1, 9)); //当前登陆人员是都是竞价部
        if (!$is_swt && !$is_jjb) {//!$is_manager && 
            $qq_access->set_custom_where(" AND (add_userid = " . $login_userid . " OR presales_id = " . $login_userid . " )");
            $where_sql.=" AND (add_userid = " . $login_userid . " OR presales_id = " . $login_userid . " )";
        }
        if (isset($sort) && isset($sortname)) {
            $qq_access->set_order_by($qq_access->get_field_by_name($sortname), $sort);
        }
        $qq_access->set_custom_order_by("IF(ISNULL(access_time),0,1) ASC");
        $qq_access->set_order_by(p_qqaccess_soft::$field_addtime, SqlSortType::Desc);
        $qq_access->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $qq_access, NULL, TRUE);
        if (!$result[0]) die_error(USER_ERROR, '获取QQ接入数据失败，请重试');
        $jjb_array = array(1, 146, 154, 162, 187, 400); //进价不这帮人看到的数据不加*(秦明,东方柏,穆桂英,木野真,靖王妃)(仅限竞价部)
        $is_sq_manager = in_array($login_userid, array(1, 161, 163, 178)); //软件部非一般人员不加      
        $is_swt = in_array($employees[$login_userid]['role_id'], array(0, 1101, 1102, 1103, 1110, 1112)); //当前登陆人员是否是商务通(包含管理员)
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($login_userid, $jjb_array, $is_jjb, $is_sq_manager, $is_swt, $dept_id) {
                    $d['hasChangePresales'] = in_array($login_userid, array(1, 161, 163, 178, 185, 186)); //有一帮特殊的人要修改QQ接入的售前
                    $d['hasEdit'] = in_array($login_userid, array(1, 161, 162, 163, 165, 166, 178, 213, 185, 186, 254)); //有编辑权限的人
                    $d['role_id'] = get_role_id($login_userid);

                    //如果是自己添加的记录，就都可以看到
                    if ($login_userid == $d['add_userid'] || $login_userid == $d['presales_id']) {
                        $d['qq_num'] = $d['qq_num'];
                        $d['customer_address'] = $d['customer_address'];
                        $d['keyword'] = $d['keyword'];
                    } elseif ($is_jjb) {
                        if (!in_array($login_userid, $jjb_array) && !str_equals($d['qq_num'], "")) {
                            $len = strlen($d['qq_num']);
                            $d['qq_num'] = substr($d['qq_num'], 0, 2) . "******" . substr($d['qq_num'], ($len - 2), $len);
                        }
                    } else {
                        if (in_array($dept_id, array(1, 9, 11))) {

                            // 如果是软件部经理，主管或者售前的人就可以看到地址和关键字
                            if (!$is_sq_manager && !in_array(get_role_id($login_userid), array(1104, 1112))) {
                                if (!$is_swt && !$is_jjb) {
                                    $d['customer_address'] = "*******";
                                    $d['keyword'] = "*******";
                                }
                            }
                            //售前的不能看到QQ
                            elseif (in_array(get_role_id($login_userid), array(1104, 1112)) && !$is_sq_manager) {
                                $len = strlen($d['qq_num']);
                                $d['qq_num'] = substr($d['qq_num'], 0, 2) . "******" . substr($d['qq_num'], ($len - 2), $len);
                            }
                        } else {
                            $d['customer_address'] = "*******";
                            $d['keyword'] = "*******";
                        }
                    }
                });

        $retArray = array('TodayBaiduPCTotals' => 0, 'TodayBaiduMTotals' => 0, 'Today360Totals' => 0, 'TodaySogouTotals' => 0, 'TodayUCTotals' => 0, 'TodayBaiduZhiDaoTotals' => 0, 'TodayWangMengTotals' => 0, 'TodayWeiXinTotals' => 0, 'TodayYouHuaZhanTotals' => 0, 'TodayNoTotals' => 0);
        $groupSumCountSql = "select p.channel ,COUNT(p.channel) c from p_qqaccess_soft p WHERE 1 " . $where_sql . " GROUP BY p.channel;";
        $result_ = Model::execute_custom_sql($db, $groupSumCountSql);
        if (!$result[0]) die_error(USER_ERROR, '获取转Q统计数据失败，请重试');
        $sumCountModel = $result_['results'];
        array_walk($sumCountModel, function(&$d) use(&$retArray) {
            if (str_equals($d['channel'], "百度(PC)")) {
                $retArray['TodayBaiduPCTotals'] = (int) $d['c'];
            } else if (str_equals($d['channel'], "百度(移动)")) {
                $retArray['TodayBaiduMTotals'] = (int) $d['c'];
            } else if (str_equals($d['channel'], "360")) {
                $retArray['Today360Totals'] = (int) $d['c'];
            } else if (str_equals($d['channel'], "搜狗")) {
                $retArray['TodaySogouTotals'] = (int) $d['c'];
            } else if (str_equals($d['channel'], "UC")) {
                $retArray['TodayUCTotals'] = (int) $d['c'];
            } else if (str_equals($d['channel'], "百度知道")) {
                $retArray['TodayBaiduZhiDaoTotals'] = (int) $d['c'];
            } else if (str_equals($d['channel'], "网盟")) {
                $retArray['TodayWangMengTotals'] = (int) $d['c'];
            } else if (str_equals($d['channel'], "微信")) {
                $retArray['TodayWeiXinTotals'] = (int) $d['c'];
            } else if (str_equals($d['channel'], "优化站")) {
                $retArray['TodayYouHuaZhanTotals'] = (int) $d['c'];
            } else if (str_equals($d['channel'], "无渠道匹配")) {
                $retArray['TodayNoTotals'] = (int) $d['c'];
            }
        });
        echo_list_result($result, $models, array('TodayTotals' => $retArray));
    }
    if ($action == 11) {
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $qqaccess = new p_qqaccess_soft();
        if (isset($startTime)) {
            $startTime = date("Y-m-d 03:00:00", strtotime($startTime));
            $qqaccess->set_custom_where(" AND DATE_FORMAT(addtime, '%Y-%m-%d %H:%i:%s') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $endTime = date("Y-m-d 02:59:59", strtotime('+1 day', strtotime($endTime)));
            $qqaccess->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d %H:%i:%s') <= '" . $endTime . "' ");
        }

        $field = array('addtime', 'add_username', 'presales', 'qq_num', 'customer_num', 'customer_address', 'channel', 'access_time', 'keyword', 'into_url');
        $qqaccess->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $qqaccess, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('QQ接入数据导出失败,请稍后重试!')), "QQ接入数据导出", "销售统计");
        }
        $models = Model::list_to_array($result['models']);
        $title_array = array('添加时间', '添加人', '售前', '客户QQ号', '客户编号', '客户地址', '渠道', '接入时间', '关键字', '跳转地址');
        $export->set_field($field);
        $export->create($title_array, $models, "QQ接入数据导出", "QQ接入");
    }
    if ($action == 12) {
        $db = create_pdo();
        $channel_site_soft = new p_channel_site_soft();
        $result = Model::query_list($db, $channel_site_soft, NULL, TRUE);
        $models = Model::list_to_array($result['models']);
        $array = array();
        foreach ($models as $channel_site_array) {
            $array[$channel_site_array['channel']].=$channel_site_array['address'] . "\r\n";
        }
        echo_result($array);
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $qq_accessData = request_object();
    if ($action == 1) {
        //根据url找到对应的渠道
        // into_url
        $db = create_pdo();
        $channel_site_soft = new p_channel_site_soft();
        $channel_site_soft->set_where_and(p_channel_site_soft::$field_address, SqlOperator::Equals, $qq_accessData->into_url);
        $channel_site_soft->load($db, $channel_site_soft);
        $channel_site_soft = $channel_site_soft->to_array();
        $channel = '无渠道匹配';
        if ($channel_site_soft['id']) {
            $channel = $channel_site_soft['channel'];
        }
        //如果QQ重复就不分配售前,FUCK CODE
        $qq_access = new p_qqaccess_soft();
        // $sql ="SELECT COUNT(0) FROM p_qqaccess_soft WHERE qq_num LIKE '%".trim($qq_accessData->qq_num)."%'";
        $sql = "SELECT COUNT(0) FROM p_qqaccess_soft WHERE qq_num = '" . trim($qq_accessData->qq_num) . "'";
        $count = Model::execute($db, $sql, $qq_access);
        $count = $count['results'];
        $count = $count[0]['COUNT(0)'];
        $qq_is_repetition = $count > 0 ? 1 : 0;
        //如果是微信接入的，就不分配售前，在编辑中手动选择      
        if ($channel == '微信' || $count > 0) {
            $presales = "";
            $presales_id = 0;
        } else {
            $sql = "SELECT pr.id,pr.`status`,pr.addtime,pr.presales,pr.presales_id,pr.toplimit,IFNULL(pa.finish,0) AS finish,pr.starttime,pr.endtime,pr.lastDistribution ";
            $sql .="FROM p_qqreception_soft pr LEFT JOIN (";
            $sql .="SELECT COUNT(pa.presales_id) AS finish,pa.presales_id FROM p_qqaccess_soft pa ";
            $sql .="WHERE " . getWhereSql('pa') . " GROUP BY pa.presales_id ) AS pa ON pr.presales_id = pa.presales_id ";
            $sql .="WHERE pr.toplimit > IFNULL(pa.finish,0) AND pr.`status` = 1 AND pr.`status0` = 1 ORDER BY pr.lastDistribution ASC LIMIT 1; ";
            $qq_reception = new p_qqreception_soft();
            $result = $qq_reception->load($db, $qq_reception, $sql);
            if (!$result[0]) die_error(USER_ERROR, '暂无QQ接待,请稍后重试~');
            $presales = $qq_reception->get_presales();
            $presales_id = $qq_reception->get_presales_id();

            $qq_reception->set_lastDistribution((microtime(TRUE) * 10000));
            $reception_result = $qq_reception->update($db);
            if (!$reception_result[0]) throw new TransactionException(PDO_ERROR_CODE, '分配失败~' . $reception_result['detail_cn'], $reception_result);
        }

        $qq_access = new p_qqaccess_soft();
        $qq_access->set_field_from_array($qq_accessData);
        $qq_access->set_add_userid(request_login_userid());
        $qq_access->set_add_username(request_login_username());
        $qq_access->set_addtime('now');
        $qq_access->set_presales($presales);
        $qq_access->set_presales_id($presales_id);
        $qq_access->set_channel($channel);
        $qq_access->set_qq_is_repetition($qq_is_repetition);
        $qq_access->set_qq_num(trim($qq_accessData->qq_num));
        if (!str_equals(($qq_accessData->validation), "")) {
            $qq_access->set_hasValidation(2);
        }
        pdo_transaction($db, function($db) use($qq_access, $qq_accessData) {
            $access_result = $qq_access->insert($db);
            if (!$access_result[0]) throw new TransactionException(PDO_ERROR_CODE, '分配失败~' . $access_result['detail_cn'], $access_result);
            add_data_add_log($db, $qq_accessData, new p_qqaccess_soft($qq_access->get_id()), 10);
        });
        //update_salestatistics($db, $qq_access->get_presales_id(), $qq_access->get_presales(), true);
//        $msg = array('Code' => 0, 'Msg' => '', 'Remark' => '', 'MsgType' => 6);
//        send_push_msg(json_encode($msg), $presales_id);
        $title = "新Q接入提醒";
        $text = "亲，您有转Q未处理，请尽快加人确认。";
        $label = "QQ号";
        $msg = array('step' => 1, 'title' => $title, 'addtime' => date("Y-m-d H:i:s"), 'username' => request_login_username(), 'caption' => $label, 'text' => $text, 'num' => 0, 'Code' => 0, 'Msg' => '', 'Remark' => '', 'MsgType' => 3);
        send_push_msg(json_encode($msg), $presales_id);
        echo_msg('分配成功~');
    }
    //修改销售售后信息
    if ($action == 2) {
        $qq_access = new p_qqaccess_soft($qq_accessData->id);
        $db = create_pdo();
        $res = $qq_access->load($db, $qq_access);
        if (!$res[0]) die_error(USER_ERROR, '保存失败');
        $qq_access->set_field_from_array($qq_accessData);
        if (isset($qq_accessData->do_access)) {
            $qq_access->set_access_time('now');
            add_data_change_log($db, $qq_accessData, new p_qqaccess_soft($qq_accessData->id), 10, '确认通过');
        } else {
            add_data_change_log($db, $qq_accessData, new p_qqaccess_soft($qq_accessData->id), 10);
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
    //删除
    if ($action == 3) {
        $qq_access = new p_qqaccess_soft();
        $qq_access->set_field_from_array($qq_accessData);
        $db = create_pdo();
        $result = $qq_access->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除失败');
        update_salestatistics($db, $qq_accessData->presales_id, $qq_accessData->presales);
        echo_msg('删除成功');
    }
    /**
     * 重新分配售前
     */
    if ($action == 4) {
        $channel = $qq_accessData->channel;
        $role_id = array(
            '百度(PC)' => 705,
            '百度(移动)' => 716,
            '360' => 706,
            '搜狗' => 711
                )[$channel];
        $sql = "SELECT pr.id,pr.`status`,pr.addtime,pr.presales,pr.presales_id,pr.toplimit,IFNULL(pa.finish,0) AS finish,pr.starttime,pr.endtime,pr.lastDistribution ";
        $sql .="FROM p_qqreception_soft pr LEFT JOIN (";
        $sql .="SELECT COUNT(pa.presales_id) AS finish,pa.presales_id FROM p_qqaccess_soft pa ";
        $sql .="WHERE " . getWhereSql('pa') . " GROUP BY pa.presales_id ) AS pa ON pr.presales_id = pa.presales_id ";
        if ($role_id != null) {
            $sql .= "INNER JOIN M_User u ON pr.presales_id = u.userid WHERE pr.presales_id != 79 AND pr.toplimit > IFNULL(pa.finish,0) AND pr.`status` = 1 AND u.role_id = " . $role_id . " ORDER BY pr.lastDistribution ASC LIMIT 1;";
        } else {
            $sql .="WHERE pr.presales_id != 79 AND pr.toplimit > IFNULL(pa.finish,0) AND pr.`status` = 1 ORDER BY pr.lastDistribution ASC LIMIT 1; ";
        }
        $db = create_pdo();
        $result = Model::execute_custom_sql($db, $sql);
        if (!$result[0]) die_error(USER_ERROR, '暂无QQ接待,请稍后重试~');
        $qq_reception = new p_qqreception_soft();
        $qq_reception->set_field_from_array($result['results'][0]);
        $qq_reception->set_lastDistribution((microtime(TRUE) * 10000));
        $qq_access = new p_qqaccess_soft($qq_accessData->id);
        $res_qq = $qq_access->load($db, $qq_access);
        if (!$res_qq[0]) die_error(USER_ERROR, "分配失败,请稍后重试~");
        $qq_access->set_presales($qq_reception->get_presales());
        $qq_access->set_presales_id($qq_reception->get_presales_id());
        $d_array = array('presales' => $qq_reception->get_presales(), 'key_names' => array('presales' => '售前'));
        pdo_transaction($db, function($db) use($qq_access, $qq_reception, $d_array) {
            add_data_change_log($db, $d_array, new p_qqaccess_soft($qq_access->get_id()), 10);
            $access_result = $qq_access->update($db, true);
            if (!$access_result[0]) throw new TransactionException(PDO_ERROR_CODE, '分配失败,请稍后重试~~' . $access_result['detail_cn'], $access_result);
            $reception_result = $qq_reception->update($db);
            if (!$reception_result[0]) throw new TransactionException(PDO_ERROR_CODE, '分配失败~' . $reception_result['detail_cn'], $reception_result);
        });
        echo_msg("分配成功~");
    }
    //渠道设置
    if ($action == 5) {
        $channel_array = array(
            '百度(pc)', '百度(移动)', '360', '搜狗', 'UC', '百度知道', '网盟'
        );
        $db = create_pdo();
        $channel_site_soft = new p_channel_site_soft();
        if (!is_array($qq_accessData->channel_site)) {
            $channel_site[] = $qq_accessData->channel_site;
        } else {
            $channel_site = $qq_accessData->channel_site;
        }

        foreach ($channel_site as $channel) {
            if (isset($qq_accessData->$channel)) {
                $site_array = nl2br($qq_accessData->$channel); //将分行符"\r\n"转义成HTML的换行符"<br />"
                $site_array = explode("<br />", $site_array); //"<br />"作为分隔切成数组
                //先将对应渠道的网址全部删除
                $sql = "DELETE FROM p_channel_site_soft WHERE channel='" . $channel . "'";
                $result_temp = Model::execute($db, $sql, $channel_site_soft);
                //  $site_array= array_filter($site_array);            

                foreach ($site_array as $site) {
                    $site = trim($site);
                    if (strlen($site) > 0) {
                        $channel_site_soft->set_channel($channel);
                        $channel_site_soft->set_address($site);
                        $result = $channel_site_soft->insert($db, true);
                    }
                }
            }
        }
        echo_msg(" 保存成功~");
    }
});

function get_group($group_id) {
    $group_array = array(
        701 => '百度(PC)',
        703 => '百度(PC)',
        705 => '百度(PC)',
        707 => '百度(PC)',
        713 => '百度(PC)',
        716 => '百度(YD)',
        702 => '360',
        704 => '360',
        706 => '360',
        708 => '360',
        714 => '360',
        709 => '搜狗',
        710 => '搜狗',
        711 => '搜狗',
        712 => '搜狗',
        715 => '搜狗',
    );
    return $group_array[$group_id];
}

/**
 * 2015-12-31 21:39 Created By Yan13
 * 获取商务通角色ID数组
 */
function get_zoosnet_role_ids() {
    //707:百度商务通(PC)
    //708:360商务通
    //712:搜狗商务通
    //717:百度商务通(YD)
    return array(0, 1101, 1102, 1103, 1110, 1112);
}

//
//四川省客人 2015-12-17 10:10:10 1232132132
//搜索关键词:加盟淘宝店需要多少钱]:m.baidu.com 进入:m.ycxmm.cn/