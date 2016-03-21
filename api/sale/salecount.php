<?php

/**
 * 销售业绩统计表
 *
 * @author QI
 * @copyright 2015 星密码
 * @version 2015/3/16
 */
use Models\Base\Model;
use Models\p_salecount;
use Models\P_Fills;
use Models\P_Upgrade;
use Models\p_salestatistics;
use Models\p_qqaccess;
use Models\p_customerstatistics;
use Models\Base\SqlOperator;
use Models\P_Customerrecord;
use Models\Base\SqlSortType;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';
require_once '../../common/http.php';
require '../../api/sale/update_salestatistics.php';
require '../../api/sale/update_customerstatistics.php';


$use_redis = false;
$redis_config = array('host' => '127.0.0.1', 'port' => 6380);
$threshold = '03:00:00';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action, $use_redis, $redis_config, $threshold) {
    $login_userid = request_login_userid();
    $is_manager = is_manager($login_userid);
    if (!isset($action)) $action = 1;
    if ($action == 1) {
        $salecount = new p_salecount();
        $sort = request_string('sort');
        $sortname = request_string('sortname');
        $searchName = request_string('searchName');
        $searchMouth = request_string("searchMouth");
        $searchSetmeal = request_string("searchSetmeal");
        $channel = request_string("channel");
        $searchStartTime = request_string("searchStartTime");
        $searchEndTime = request_string("searchEndTime");
        $status = request_int("status");
        //  $changeNickName = request_int('changeNickName');
        if (isset($searchName)) {
            $salecount->set_custom_where(" AND ( nick_name like '%" . $searchName . "%' OR money = '" . $searchName . "' OR mobile like '%" . $searchName . "%' OR  ww like '%" . $searchName . "%' OR name LIKE '%" . $searchName . "%' OR qq like'%" . $searchName . "%' OR presales like '%" . $searchName . "%' OR customer LIKE '%" . $searchName . "%' ) ");
        }
        if (isset($searchSetmeal)) {
            $salecount->set_where_and(p_salecount::$field_setmeal, SqlOperator::Equals, $searchSetmeal);
        }
        if (isset($searchMouth)) {
            $searchDate = date('Y') . '-' . $searchMouth;
            $salecount->set_custom_where(" AND DATE_FORMAT(addtime,'%Y-%m') = '" . $searchDate . "' ");
        } else {
            if (!isset($searchName) && !isset($salecount)) {
                $salecount->set_custom_where(" AND DATE_FORMAT(addtime,'%Y-%m-%d') = '" . date('Y-m-d') . "' ");
            }
        }
        if (isset($searchStartTime)) {
            $formatStr = '%Y-%m-%d';
            if (strlen($searchStartTime) > 10) {
                $formatStr = "%Y-%m-%d %H:%i";
            }
            $salecount->set_custom_where(" and DATE_FORMAT(addtime, '" . $formatStr . "') >= '" . $searchStartTime . "' ");
        }
        if (isset($searchEndTime)) {
            $formatStr = '%Y-%m-%d';
            if (strlen($searchEndTime) > 10) {
                $formatStr = "%Y-%m-%d %H:%i";
            }
            $salecount->set_custom_where(" and DATE_FORMAT(addtime, '" . $formatStr . "') <= '" . $searchEndTime . "' ");
        }
        if (isset($channel)) {
            $salecount->set_where_and(p_salecount::$field_channel, SqlOperator::Equals, $channel);
        }
        if (isset($status)) {
            if ($status == 1) {
                $salecount->set_where_and(p_salecount::$field_status, SqlOperator::Equals, 1);
            } else if ($status == 2) {
                $salecount->set_where_and(p_salecount::$field_conflictWith, SqlOperator::NotEquals, 0);
            } else if ($status == 3) {
                $salecount->set_where_and(p_salecount::$field_customer_id, SqlOperator::Equals, 0);
            }
        }
        if (isset($changeNickName)) {
            $salecount->set_where_and(p_salecount::$field_nick_name2, SqlOperator::IsNotNull);
        }
        //if (!$is_manager) {
        //    $salecount->set_custom_where(" and  (customer_id = " . $login_userid . " or presales_id=" . $login_userid . " )");
        //}
        if (isset($sort) && isset($sortname)) {
            $salecount->set_order_by($salecount->get_field_by_name($sortname), $sort);
        } else {
            $salecount->set_order_by(p_salecount::$field_status, SqlSortType::Desc);
            $salecount->set_order_by(p_salecount::$field_addtime, 'DESC');
        }
        $salecount->set_limit_paged(request_pageno(), request_pagesize());
        $db = create_pdo();
        $result = Model::query_list($db, $salecount, NULL, true);
        if (!$result[0]) die_error(USER_ERROR, '获取统计资料失败，请重试');
        $models = Model::list_to_array($result['models'], array(), function(&$d) use($is_manager, $login_userid) {
                    if (!$is_manager && !str_equals($d['presales_id'], $login_userid) && !str_equals($d['customer_id'], $login_userid)) {
                        $d['ww'] = '****';
                        $d['name'] = '***';
                        $d['qq'] = '********';
                        $d['mobile'] = '***********';
                        $d['province'] = '***';
                    }
                });
        echo_list_result($result, $models, array('currentDate' => date('Y-m-d'), 'is_manager' => $is_manager));
    }
    //排名
    if ($action == 2) {
        $time_unit = request_int('time_unit', 1, 3);
        $salecount = new p_salecount();
        $sql = 'SELECT A.presales_id,A.presales,us.role_id as group_id,B.sale_count,B.money FROM p_salecount A,(SELECT id,COUNT(*) AS sale_count,MAX(id) AS max_id ,SUM(money) money FROM p_salecount WHERE 1=1 ';
        switch ($time_unit) {
            case 1:
                $h = (int) date("H");
                if ($h < 3) {
                    $sql.= "AND '" . date('Y-m-d', strtotime("-1 day")) . " 03:00:00' <=  DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') <= '" . date('Y-m-d') . " 02:59:59' ";
                } else {
                    $sql.= "AND '" . date('Y-m-d') . " 03:00:00' <=  DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') <= '" . date('Y-m-d', strtotime("+1 day")) . " 02:59:59' ";
                }
                break;
            case 2:
                $date = date('Y-m-d');  //当前日期
                $first = 1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
                $w = date('w', strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6 
                $now_start = date('Y-m-d', strtotime("$date -" . ($w ? $w - $first : 6) . ' days')) . ' 03:00:00'; //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
                $now_end = date('Y-m-d', strtotime("$now_start +7 days")) . ' 02:59:59';  //本周结束日期
                $sql.= "AND '" . $now_start . "' <=  DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') <= '" . $now_end . "' ";
                break;
            case 3:
                $date = date('Y-m-d');
                $firstday = date('Y-m-01') . ' 03:00:00';
                $lastday = date('Y-m-01', strtotime(date('Y-m-01', strtotime($date)) . ' +1 month')) . ' 02:59:59';
                $sql.= "AND '" . $firstday . "' <=  DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') <= '" . $lastday . "' ";
                break;
        }
        $sql .='GROUP BY presales_id) B,M_user us WHERE  A.id = B.max_id AND us.userid = A.presales_id ORDER BY sale_count DESC';
        $db = create_pdo();
        $result = Model::execute_custom_sql($db, $sql);
        if (!$result[0]) die_error(USER_ERROR, '获取排名数据失败，请重试');
        $result = $result['results'];
        if (!empty($result)) {
            array_walk($result, function(&$item) {
                if (in_array($item['group_id'], array('701', '703', '705', '707', '713'))) {
                    $item['presales'] = $item['presales'] . '(百度PC)';
                } else if (in_array($item['group_id'], array('716'))) {
                    $item['presales'] = $item['presales'] . '(百度YD)';
                } elseif (in_array($item['group_id'], array('702', '704', '706', '708', '714'))) {
                    $item['presales'] = $item['presales'] . '(360)';
                } elseif (in_array($item['group_id'], array('709', '710', '711', '712', '715'))) {
                    $item['presales'] = $item['presales'] . '(搜狗)';
                }
            });
        }
        echo_result($result);
    }
    if ($action == 3) {
        $key_word = request_string("key_word");
        if (str_equals($key_word, "无")) echo_result(array());
        $salecount = new p_salecount();
        $salecount->set_query_fields(array('id', 'addtime', 'ww', 'arrears', 'name', 'qq', 'money', 'mobile', 'payment', 'channel', 'presales', 'presales_id', 'customer', 'customer_id', 'nick_name', 'isQQTeach', 'scheduledPackage'));
        $salecount->set_custom_where(" AND ( ww = '" . $key_word . "' OR qq = '" . $key_word . "' OR name = '" . $key_word . "' OR mobile = '" . $key_word . "' ) ");
        $db = create_pdo();
        $result = $salecount->query_list($db, $salecount);
        $list = Model::list_to_array($result['models']);
        echo_result($list);
    }
    if ($action == 4) {
        $retArray = array('TodayBaiduPCTotals' => 0, 'TodayBaiduPCTimelyTotals' => 0, 'TodayBaiduMTotals' => 0, 'TodayBaiduMTimelyTotals' => 0, 'Today360Totals' => 0, 'Today360TimelyTotals' => 0, 'TodaySogouTotals' => 0, 'TodaySogouTimelyTotals' => 0, 'TodaySmTotals' => 0, 'TodaySmTimelyTotals' => 0);
        $redis = new Redis();
        $redis_connected = $redis->connect($redis_config['host'], $redis_config['port']);
        if ($use_redis) {
            $use_redis = $redis_connected;
            if ($redis_connected === true) {
                $date_adjusted_format = adjust_time('Y_m_d', $threshold) . '_';
                array_walk($retArray, function(&$val, $key) use($redis, $date_adjusted_format) {
                    $val = (int) $redis->get($date_adjusted_format . $key);
                });
            }
        }
        if (!$use_redis) {
            array_walk($retArray, function(&$val, $key) {
                $val = 0;
            });
            $groupSumCountSql = "SELECT ps.group_id,ps.isTimely,ps.channel FROM p_salecount ps WHERE " . getWhereSql("ps");
            $salecount_count = new p_salecount();
            $db = create_pdo();
            $groupSumCountResult = Model::query_list($db, $salecount_count, $groupSumCountSql);
            $sumCountModel = Model::list_to_array($groupSumCountResult['models']);
            foreach ($sumCountModel as $model) {
                $group_name = get_group_by_channel($model['channel']);
                $timely = isset($model['isTimely']) ? $model['isTimely'] : 0;
                if (str_equals($group_name, "百度(PC)")) {
                    $retArray['TodayBaiduPCTotals'] += 1;
                    if ($timely == 1) {
                        $retArray['TodayBaiduPCTimelyTotals'] += 1;
                    }
                } else if (str_equals($group_name, "百度(YD)")) {
                    $retArray['TodayBaiduMTotals'] += 1;
                    if ($timely == 1) {
                        $retArray['TodayBaiduMTimelyTotals'] += 1;
                    }
                } else if (str_equals($group_name, "360")) {
                    $retArray['Today360Totals'] += 1;
                    if ($timely == 1) {
                        $retArray['Today360TimelyTotals'] += 1;
                    }
                } else if (str_equals($group_name, "搜狗")) {
                    $retArray['TodaySogouTotals'] += 1;
                    if ($timely == 1) {
                        $retArray['TodaySogouTimelyTotals'] += 1;
                    }
                } else if (str_equals($group_name, "神马")) {
                    $retArray['TodaySmTotals'] += 1;
                    if ($timely == 1) {
                        $retArray['TodaySmTimelyTotals'] += 1;
                    }
                }
            }
            if ($redis_connected) {
                $date_adjusted_format = adjust_time('Y_m_d', $threshold) . '_';
                foreach ($retArray as $key => $value) {
                    $redis->set($date_adjusted_format . $key, $value);
                }
                $redis->set($date_adjusted_format . 'TodayTotals', ($retArray['TodayBaiduPCTotals'] + $retArray['TodayBaiduMTotals'] + $retArray['Today360Totals'] + $retArray['TodaySogouTotals'] + $retArray['TodaySmTotals']));
                $redis->set($date_adjusted_format . 'TodayTotalsTimely', ($retArray['TodayBaiduPCTimelyTotals'] + $retArray['TodayBaiduMTimelyTotals'] + $retArray['Today360TimelyTotals'] + $retArray['TodaySogouTimelyTotals'] + $retArray['TodaySmTimelyTotals']));
            }
        }
        echo_result($retArray);
    }
    if ($action == 10) {
        $db = create_pdo();
        $customer = new P_Customerrecord();
        $customer->set_status(1);
        $customer->set_query_fields(array('userid', 'username', 'nickname', 'qqReception', 'tmallReception_qj', 'tmallReception_zy'));
        $customer_result = Model::query_list($db, $customer);
        $customer_list = Model::list_to_array($customer_result['models'], array(), function(&$d) {
                    $d['id'] = $d['userid'];
                    $d['text'] = $d['username'] . "(" . $d['nickname'] . ")";
                    unset($d['userid']);
                    unset($d['username']);
                    unset($d['nickname']);
                });
        echo_result(array('customer_list' => $customer_list, 'code' => 0));
    }
    if ($action == 11) {
        $type = request_int("type");
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $salecount = new p_salecount();

        if ($type === 1) {
            if (isset($startTime)) {
                $startTime = date("Y-m-d 03:00:00", strtotime($startTime));
                $salecount->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d %H:%i:%s') >= '" . $startTime . "' ");
            }
            if (isset($endTime)) {
                $endTime = date("Y-m-d 03:00:00", strtotime('+1 day', strtotime($endTime)));
                $salecount->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d %H:%i:%s') <= '" . $endTime . "' ");
            }
        } else {
            if (isset($startTime)) {
                $startTime = date("Y-m-d 03:00:00", strtotime($startTime));
                $salecount->set_custom_where(" and DATE_FORMAT(customer_date, '%Y-%m-%d %H:%i:%s') >= '" . $startTime . "' ");
            }
            if (isset($endTime)) {
                $endTime = date("Y-m-d 03:00:00", strtotime('+1 day', strtotime($endTime)));
                $salecount->set_custom_where(" and DATE_FORMAT(customer_date, '%Y-%m-%d %H:%i:%s') <= '" . $endTime . "' ");
            }
        }
        $field = array('addtime', 'ww', 'name', 'qq', 'mobile', 'money', 'arrears', 'setmeal', 'payment', 'channel', 'isTimely', 'presales', 'presales_cashback', 'customer', 'province', 'address', 'remark');
        $salecount->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $salecount, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('销售统计数据导出失败,请稍后重试!')), "销售统计数据导出", "销售统计");
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $d['isTimely'] = $d['isTimely'] === 0 ? "否" : '是';
                });
        $title_array = array('日期', '旺旺号', '真实姓名', 'QQ号', '手机号', '金额', '欠款', '套餐类型', '收款方式', '接入渠道', '是否及时', '售前', '售前返现', '售后', '省份', '地址', '备注');
        $export->set_field($field);
        $export->set_field_width(array(20, 20, 10, 12, 13, 8, 8, 10, 10, 10, 15, 9, 10, 10, 10, 20, 30));
        $export->create($title_array, $models, "销售统计数据导出", "销售统计");
    }
    /**
     * 更换老师
     */
    if ($action == 12) {
        $startTime = request_datetime("start_time");
        $endTime = request_datetime("end_time");
        $export = new ExportData2Excel();
        $salecount = new p_salecount();
        if (isset($startTime)) {
            $salecount->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') >= '" . $startTime . "' ");
        }
        if (isset($endTime)) {
            $salecount->set_custom_where(" and DATE_FORMAT(addtime, '%Y-%m-%d') <= '" . $endTime . "' ");
        }
        $salecount->set_where_and(p_salecount::$field_nick_name2, SqlOperator::IsNotNull);
        $field = array('addtime', 'ww', 'name', 'qq', 'mobile', 'money', 'setmeal', 'province', 'payment', 'channel', 'isTimely', 'isQQTeach', 'isTmallTeach_qj', 'isTmallTeach_zy', 'arrears', 'presales', 'customer', 'customer2');
        $salecount->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $salecount, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('销售统计数据导出失败,请稍后重试!')), "销售统计数据导出", "销售统计");
        }
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $d['isTimely'] = $d['isTimely'] === 0 ? "否" : '是';
                    $d['isQQTeach'] = $d['isQQTeach'] === 0 ? "否" : '是';
                    $d['isTmallTeach_qj'] = $d['isTmallTeach_qj'] === 0 ? "否" : '是';
                    $d['isTmallTeach_zy'] = $d['isTmallTeach_zy'] === 0 ? "否" : '是';
                });
        $title_array = array('日期', '旺旺号', '真实姓名', 'QQ号', '手机号', '金额', '套餐类型', '省份', '收款方式', '接入渠道', '是否及时', 'QQ教学', '天猫旗舰教学', '天猫专营教学', '欠款', '售前', '售后', '更换老师');
        $export->set_field($field);
        $export->create($title_array, $models, "销售统计数据导出", "销售统计");
    }
    /**
     * 二销补欠款自动补全查询
     */
    if ($action == 20) {
        $qk = request_string("qk");
        $salecount = new p_salecount();
        //旺旺/QQ/真实姓名
        $salecount->set_custom_where(" AND ( ww = '" . $qk . "' OR qq = '" . $qk . "' OR mobile = '" . $qk . "' ) "); //"' OR name = '" . $qk . 
        $salecount->set_query_fields(array('ww', 'qq', 'mobile')); // 'name',
        $db = create_pdo();
        $result = Model::query_list($db, $salecount);
        if (!$result[0]) die_error(USER_ERROR, '获取统计资料失败，请重试');
        $models = Model::list_to_array($result['models']);
        echo_result($models);
    }
    //查询历史统计数据
    if ($action == 100) {
        $date = request_datetime('date');
        $date = date('Y-m-d', strtotime($date));
        $use_redis = request_int('use_redis', 0, 1);
        $retArray = array('TodayBaiduPCTotals' => 0, 'TodayBaiduPCTimelyTotals' => 0, 'TodayBaiduMTotals' => 0, 'TodayBaiduMTimelyTotals' => 0, 'Today360Totals' => 0, 'Today360TimelyTotals' => 0, 'TodaySogouTotals' => 0, 'TodaySogouTimelyTotals' => 0);
        $redis = new Redis();
        $redis_connected = $redis->connect($redis_config['host'], $redis_config['port']);
        if ($use_redis == 1) {
            $use_redis = $redis_connected;
            if ($redis_connected === true) {
                $date_adjusted_format = date('Y_m_d', strtotime($date)) . '_';
                array_walk($retArray, function(&$val, $key) use($redis, $date_adjusted_format) {
                    $val = (int) $redis->get($date_adjusted_format . $key);
                });
            }
        }
        if ($use_redis != 1) {
            array_walk($retArray, function(&$val, $key) {
                $val = 0;
            });
            $whereSql = " '" . $date . " 03:00:00' <=  DATE_FORMAT(ps.addtime,'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(ps.addtime,'%Y-%m-%d %H:%i:%s') <= '" . get_tomorrow($date) . " 02:59:59' ";
            $groupSumCountSql = "SELECT ps.group_id,ps.isTimely,ps.channel FROM p_salecount ps WHERE " . $whereSql;
            $salecount_count = new p_salecount();
            $db = create_pdo();
            $groupSumCountResult = Model::query_list($db, $salecount_count, $groupSumCountSql);
            $sumCountModel = Model::list_to_array($groupSumCountResult['models']);
            foreach ($sumCountModel as $model) {
                $grout_id = $model['group_id'];
                $group_name = get_group($grout_id);
                $group_name = get_group_by_channel($model['channel']);
                $timely = isset($model['isTimely']) ? $model['isTimely'] : 0;
                if (str_equals($group_name, "百度(PC)")) {
                    $retArray['TodayBaiduPCTotals'] += 1;
                    if ($timely == 1) {
                        $retArray['TodayBaiduPCTimelyTotals'] += 1;
                    }
                } else if (str_equals($group_name, "百度(YD)")) {
                    $retArray['TodayBaiduMTotals'] += 1;
                    if ($timely == 1) {
                        $retArray['TodayBaiduMTimelyTotals'] += 1;
                    }
                } else if (str_equals($group_name, "360")) {
                    $retArray['Today360Totals'] += 1;
                    if ($timely == 1) {
                        $retArray['Today360TimelyTotals'] += 1;
                    }
                } else if (str_equals($group_name, "搜狗")) {
                    $retArray['TodaySogouTotals'] += 1;
                    if ($timely == 1) {
                        $retArray['TodaySogouTimelyTotals'] += 1;
                    }
                }
            }
//            if ($redis_connected) {
//                $date_adjusted_format = date('Y_m_d', strtotime($date)) . '_';
//                foreach ($retArray as $key => $value) {
//                    $redis->set($date_adjusted_format . $key, $value);
//                }
//                $redis->set($date_adjusted_format . 'TodayTotals', ($retArray['TodayBaiduPCTotals'] + $retArray['TodayBaiduMTotals'] + $retArray['Today360Totals'] + $retArray['TodaySogouTotals']));
//                $redis->set($date_adjusted_format . 'TodayTotalsTimely', ($retArray['TodayBaiduPCTimelyTotals'] + $retArray['TodayBaiduMTimelyTotals'] + $retArray['Today360TimelyTotals'] + $retArray['TodaySogouTimelyTotals']));
//            }
        }
        echo_result($retArray);
    }
});

execute_request(HttpRequestMethod::Post, function() use($action, $use_redis, $redis_config, $threshold) {

    $salecountData = request_object();

    filter_numeric($salecountData->arrears, 0);
    if ($action == 1) {
        $db = create_pdo();
        $salecountCount = new p_salecount();
        if ($salecountData->ww != '无') {
            $salecountCount->set_where_and(p_salecount::$field_ww, SqlOperator::Equals, $salecountData->ww);
            $salecountCount->set_where_or(p_salecount::$field_qq, SqlOperator::Equals, $salecountData->qq);
        } else {
            $salecountCount->set_where_and(p_salecount::$field_qq, SqlOperator::Equals, $salecountData->qq);
        }
        if (($salecountData->mobile) != "") {
            $salecountCount->set_where_or(p_salecount::$field_mobile, SqlOperator::Equals, $salecountData->mobile);
        }
        $salecountCount->set_order_by(p_salecount::$field_addtime, SqlSortType::Asc);
        $result = Model::query_list($db, $salecountCount);
        if (!$result[0]) die_error(USER_ERROR, '添加失败,请稍后重试~');
        $models = Model::list_to_array($result['models']);

        $salecount = new p_salecount();
        $salecount->set_field_from_array($salecountData);

        $employees = get_employees();
        $u_id = request_login_userid();
        $dept1_id = $employees[$u_id]['dept1_id'];
        if ($dept1_id == 4) {
            if (!isset($salecount->addtime)) {
                $salecount->set_addtime('now');
            }
        } else {
            $salecount->set_addtime('now');
        }

        $group_id = $employees[$salecountData->presales_id]['role_id'];
        $salecount->set_group_id($group_id);

        $echo_msg = '保存成功~';
        //查询当前录入的用户之前是否有记录，有就是冲突了
        if ($result['count'] >= 1) {
            $salecount->set_status(0);
            $salecount->set_conflictWith($models[0]['id']);
            $update_status_sql = "update p_salecount set status = 0 where id = " . $models[0]['id'];
            pdo_transaction($db, function($db) use($update_status_sql, $salecount, $salecountData) {
                $result = Model::execute_custom_sql($db, $update_status_sql);
                if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存统计资料失败。' . $result['detail_cn'], $result);
                $result = $salecount->insert($db);
                if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存统计资料失败。' . $result['detail_cn'], $result);
                add_data_add_log($db, $salecountData, new p_salecount($salecount->get_id()), 2);
            });
            $echo_msg = "<label style='color:red;'>保存统计资料成功,但与已录入数据冲突,请及时处理</label>~";
            //如果录入的信息在数据库中找不到，就是该用户是第一次购买
        }else {
            if (str_equals($salecountData->channel, '天猫') && str_equals($salecountData->payment, '天猫')) {
                $salecount->set_status(1);
                $salecount->set_customer("花满楼");
                $salecount->set_customer_id(38);
                $salecount->set_nick_name("邓老师");
                $salecount->set_customer_date('now');
                $result = $salecount->insert($db);
                if (!$result) die_error(USER_ERROR, '添加销售统计资料失败~');
                //添加操作记录
                add_data_add_log($db, $salecountData, new p_salecount($salecount->get_id()), 2);
                $echo_msg = '添加销售统计资料成功~';
            }/** else if (isset($salecountData->status) && ($salecountData->status) == '2') {
              $salecount->set_status(2);
              $result = $salecount->insert($db);
              if (!$result) die_error(USER_ERROR, '补添加销售统计资料失败~');
              //添加操作记录
              add_data_add_log($db, $salecountData, new p_salecount($salecount->get_id()), 2);
              $echo_msg = '补添加销售统计资料成功~';
              } */else {
                $customerres_count = 0;
                //如果是预定礼包就先不用指派售后人员
                if ($salecountData->scheduledPackage == 1) {

                    $salecount->set_status(1);
                    $result = $salecount->insert($db);
                    if (!$result) die_error(USER_ERROR, '添加销售统计资料失败~');
                    add_data_add_log($db, $salecountData, new p_salecount($salecount->get_id()), 2);
                    //如果不是预定的礼包，就在售后名单中查询有没有合适的售后分配
                }else {
                    $update_distribution_sql = "";
                    $customer = new P_Customerrecord();
                    //0:接单上限   1：正常
                    $sql = "SELECT pc.id,pc.userid,pc.username,pc.nickname,pc.team,pc.group,pc.toplimit,IFNULL(ps.finish,0) AS finish ,pc.`status`,pc.lastDistribution,pc.qqReception,pc.starttime,pc.endtime ";
                    $sql .= "FROM P_Customerrecord pc ";
                    $sql .= "LEFT JOIN ( ";
                    $sql .= "SELECT sa.customer,sa.customer_id, IFNULL(COUNT(sa.customer_id),0) AS finish FROM p_salecount sa WHERE " . getWhereSql("sa") . " AND sa.customer_id != 0 GROUP BY sa.customer_id ";
                    $sql .= ") AS ps ON pc.userid = ps.customer_id ";
                    $sql .= "WHERE pc.toplimit > IFNULL(ps.finish,0) AND pc.`status` = 1 ";
                    $sql .= "AND pc.team = " . get_channel($salecountData->channel) . " ";
                    if (($salecountData->isTmallTeach_qj == 0) && ($salecountData->isTmallTeach_zy == 0)) {
                        $sql .= " AND pc.cShop = 1 ";
                    } else {
                        if ($salecountData->isTmallTeach_qj == 1) {
                            $sql .= " AND pc.tmallReception_qj = 1 ";
                        }
                        if ($salecountData->isTmallTeach_zy == 1) {
                            $sql .= " AND pc.tmallReception_zy = 1 ";
                        }
                    }
                    if ($salecountData->isQQTeach == 1) {//QQ教学 指定分配
                        $sql .= " AND pc.qqReception = 1 ";
                    }
                    $sql .= " ORDER BY pc.lastDistribution ASC ";
                    $customerres = Model::query_list($db, $customer, $sql);
                    $models = Model::list_to_array($customerres['models']);
                    $customerres_count = $customerres['count'];
                    //如果找到合适的售后来分配
                    if ($customerres_count != 0) {
                        $user = $models[0];
                        $userid = $user['userid'];
                        $username = $user['username'];
                        $nickName = $user['nickname'];
                        $team = $user['team'];
                        $group = $user['group'];
                        $toplimit = $user['toplimit'];
                        $finish = $user['finish'];
                        $salecount->set_customer_id($userid);
                        $salecount->set_customer($username);
                        $salecount->set_nick_name($nickName);
                        $salecount->set_customer_date('now');
                        $salecount->set_status(1);
                        $update_distribution_sql = "update P_Customerrecord SET `finish` = `finish` + 1, lastDistribution = '" . (microtime(TRUE) * 10000) . "' where id = " . $user['id'] . ' AND `finish` < `toplimit`';
                        pdo_transaction($db, function($db) use($salecount, $update_distribution_sql, $salecountData) {
                            if ($update_distribution_sql != '') {
                                $cresult = Model::execute_custom_sql($db, $update_distribution_sql);
                                if (!$cresult[0]) throw new TransactionException(PDO_ERROR_CODE, '添加销售统计资料失败。' . $cresult['detail_cn'], $cresult);
                            }
                            $result = $salecount->insert($db);
                            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '添加销售统计资料失败。' . $result['detail_cn'], $result);
                            add_data_add_log($db, $salecountData, new p_salecount($salecount->get_id()), 2);
                        });
                    } else {
                        pdo_transaction($db, function($db) use($salecount, $salecountData) {
                            $result = $salecount->insert($db);
                            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '添加销售统计资料失败。' . $result['detail_cn'], $result);
                            add_data_add_log($db, $salecountData, new p_salecount($salecount->get_id()), 2);
                        });
                    }
                }
                $echo_msg = $customerres_count != 0 ? '添加成功~' : '添加成功,但未分配售后~';
                if ($customerres_count != 0) {
                    $action = 0;
                    $date = 0;
                    $ind_refund_rate = 0;
                    $isupdate = 1;
                    update_customerstatistics($db, $salecount->get_customer_id(), $salecount->get_money(), $action, $date, $ind_refund_rate, $isupdate);
                }
            }
        }
        update_salestatistics($db, $salecount->get_presales_id(), $salecount->get_presales());

        if ($use_redis) {
            //更新统计信息+1

            refresh_sale_statistics('incr', $group_id, $salecountData, $redis_config, $threshold);
        }
        //推送消息

        $push_array = get_push_array($salecountData->presales_id, $db, $use_redis, $redis_config, $threshold); //暂时注释掉，这句引起报错，分析是redis类所在文件没有引用

        $msg = json_encode($push_array);
        $users = get_receive_sale_msg_userids(7);
        send_push_msg($msg, implode(',', $users));
        //输出
        echo_msg($echo_msg);
    }
    //修改销售统计信息或者更换老师
    if ($action == 2) {
        $sale_count = new p_salecount();
        $sale_count->set_where_and(p_salecount::$field_id, SqlOperator::Equals, $salecountData->id);
        $db = create_pdo();
        $sale_count_res = $sale_count->load($db, $sale_count);
        if (!$sale_count_res['0']) die_error(USER_ERROR, '获取资料失败');
        $sale_count_res = $sale_count->to_array();
        $date = $sale_count_res['customer_date'];
        $receive_money = $sale_count_res['money'];
        //财务审核也走的action=2，但是此时没有分配售后，所以要排除掉财务审核
        if (!isset($salecountData->goPass)) {
            //如果提交的表单没有包括钱就说明是更换老师
            $action = 0;
            $ind_refund_rate = 0;
            if (!isset($salecountData->money)) {
                //更换老师，如果A老师今天有500补欠款，那么换给B老师时，这500也要给B老师
                $fill_sum = 0;
                $fillarrears_old = new P_Fills();
                $fillarrears_old->set_where_and(P_Fills::$field_sale_id, SqlOperator::Equals, $salecountData->id);
                $fillarrears_old->set_query_fields(array('id', 'fill_sum', 'add_time'));
                $result = Model::query_list($db, $fillarrears_old);

                if ($result['count']) {
                    $models = Model::list_to_array($result['models']);
                    foreach ($models as $data_array) {
                        if (is_today($data_array['add_time'])) {
                            $fill_sum+=$data_array['fill_sum'];
                            //如果是当天，补欠款的售后也要跟着变
                            $fillarrears = new P_Fills($data_array['id']);
                            $fillarrears->set_field_from_array(array('customer' => $salecountData->customer, 'customer_id' => $salecountData->customer_id, 'nick_name' => $salecountData->nick_name));
                            $result = $fillarrears->update($db, true);
                        }
                    }
                }
                //如果不是当天，销售金额也不跟着更换后的老师走    
                $receive_money = is_today($date) ? $receive_money : 0;
                $money = $receive_money + $fill_sum;
                $customer_old = $sale_count_res['customer_id'];
                //如果是當天的話，就需要將更換前老師的接待數減一
                $isupdate = is_today($date) ? -1 : 0;
                update_customerstatistics($db, $customer_old, -$money, $action, 0, $ind_refund_rate, $isupdate);
                //如果是当天并且有销售金额就将更换后的售后接单数加一否则不加。没有销售金额只有补欠款的话，接待数不加一
                $isupdate = 0;
                if (is_today($date) && $receive_money > 0) {
                    $isupdate = 1;
                }
                update_customerstatistics($db, $salecountData->customer_id, $money, $action, 0, $ind_refund_rate, $isupdate);
            }
            //否则如果有售后ID就是修改销售统计记录，没有售后ID说明是编辑还没有分配售后的记录
            elseif ($salecountData->customer_id) {
                $money = (($salecountData->money) - $sale_count_res['money']);
                update_customerstatistics($db, $salecountData->customer_id, $money, $action, $date);
            }
        }

        $salecount = new p_salecount($salecountData->id);
        $salecount->set_field_from_array($salecountData);
        //添加更改记录
        if (isset($salecountData->goPass)) {
            add_data_change_log($db, $salecountData, new p_salecount($salecountData->id), 2, "审核通过");
        } else {
            add_data_change_log($db, $salecountData, new p_salecount($salecountData->id), 2);
        }

        $result = $salecount->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '保存统计资料失败');
        echo_msg('保存成功');
    }
    //删除
    if ($action == 3) {
        $salecount = new p_salecount();
        $conflictWith = $salecountData->conflictWith;
        $change_status_sql = "";
        $change_conflict_sql = "";
        $conflict_id = 0;
        $db = create_pdo();
        if ($conflictWith != 0) {
            $salecount->set_where_and(p_salecount::$field_conflictWith, SqlOperator::Equals, $conflictWith);
            $salecount_list_results = Model::query_list($db, $salecount);
            if (!$salecount_list_results[0]) die_error(USER_ERROR, '重新分配失败,请稍后重试~');
            if ($salecount_list_results['count'] == 1) {
                $change_status_sql = "update p_salecount SET STATUS = 1 WHERE id =" . $conflictWith;
            }
        } else {
            $salecount->set_where_and(p_salecount::$field_conflictWith, SqlOperator::Equals, $salecountData->id);
            $salecount_list_results = Model::query_list($db, $salecount);
            $models = Model::list_to_array($salecount_list_results['models']);
            if (count($models) != 0) {
                $conflict_id = $models[0]['id'];
                $change_conflict_sql = "update p_salecount SET conflictWith = " . $conflict_id . ' WHERE conflictWith = ' . $salecountData->id;
            }
        }
        $salecount->reset();
        $salecount->set_id($salecountData->id);
        //售后统计功能模块
        $sale_count = new p_salecount();
        $sale_count->set_where_and(p_salecount::$field_id, SqlOperator::Equals, $salecountData->id);
        $db = create_pdo();
        $sale_count_res = $sale_count->load($db, $sale_count);
        if (!$sale_count_res['0']) die_error(USER_ERROR, '获取资料失败');
        $sale_count_res = $sale_count->to_array();
        $date = $sale_count_res['customer_date'];
        $customer_id = $sale_count_res['customer_id'];
        //$customer_id为0说明是还没有分配售后人员
        if ($customer_id) {
            $action = 0;
            $ind_refund_rate = 0;
            $isupdate = -1;
            //删除操作要将对应日期的对应售后人员的接待数减一，金额减去对应数值。
            update_customerstatistics($db, $customer_id, -($salecountData->money), $action, $date, $ind_refund_rate, $isupdate);
        }
        pdo_transaction($db, function($db) use($salecount, $change_status_sql, $change_conflict_sql) {
            if ($change_status_sql != '') {
                $csresult = Model::execute_custom_sql($db, $change_status_sql);
                if (!$csresult[0]) throw new TransactionException(PDO_ERROR_CODE, '删除失败~' . $cresult['detail_cn'], $cresult);
            }
            if ($change_conflict_sql != '') {
                $wsresult = Model::execute_custom_sql($db, $change_conflict_sql);
                if (!$wsresult[0]) throw new TransactionException(PDO_ERROR_CODE, '删除失败~' . $wsresult['detail_cn'], $wsresult);
            }
            $result = $salecount->delete($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '删除失败~' . $result['detail_cn'], $result);
        });
        update_salestatistics($db, $salecountData->presales_id, $salecountData->presales);
        if ($use_redis) {
            $employees = get_employees();
            $group_id = $employees[$salecountData->presales_id]['role_id'];
            //更新统计信息-1
            refresh_sale_statistics('decr', $group_id, $salecountData, $redis_config, $threshold);
        }

        echo_result(array("code" => 0, "msg" => "删除成功", "conflict_id" => $conflict_id));
    }
    //分配
    if ($action == 4) {
        $id = request_int('sale_id');
        if (!isset($id)) die_error(USER_ERROR, '操作失败,请稍后重试~');
        $salecount = new p_salecount($id);
        $db = create_pdo();
        $salecount->load($db, $salecount);
        $salecount_res = $salecount->to_array();

        $status = $salecount_res['status'];
        $money = $salecount_res['money'];
        // $date = $salecount_res['addtime'];
        $isQQTeach = $salecount_res['isQQTeach'];
        $isTmallTeach_qj = $salecount_res['isTmallTeach_qj'];
        $isTmallTeach_zy = $salecount_res['isTmallTeach_zy'];

        $update_distribution_sql = "";
        $change_status_sql = "";
        $customer = new P_Customerrecord();
        //0:接单上限   1：正常
//        $sql = "SELECT pc.id,pc.userid,pc.username,pc.nickname,pc.team,pc.group,pc.toplimit,IFNULL(ps.finish,0) AS finish ,pc.`status`,pc.lastDistribution,pc.qqReception,pc.starttime,pc.endtime ";
//        $sql .= "FROM P_Customerrecord pc ";
//        $sql .= "LEFT JOIN ( ";
//        $sql .= "SELECT sa.customer,sa.customer_id, IFNULL(COUNT(sa.customer_id),0) AS finish FROM p_salecount sa WHERE " . getWhereSql("sa") . " AND sa.customer_id != 0 GROUP BY sa.customer_id ";
//        $sql .= ") AS ps ON pc.userid = ps.customer_id ";
//        $sql .= "WHERE pc.toplimit > IFNULL(ps.finish,0) AND pc.`status` = 1 ";
//        $sql .= "AND pc.team = " . get_channel($salecount->get_channel()) . " ";
        $sql = "SELECT pc.id,pc.userid,pc.username,pc.nickname,pc.team,pc.group,pc.toplimit,pc.finish,pc.`status`,pc.lastDistribution,pc.qqReception,pc.starttime,pc.endtime  ";
        $sql .= "FROM P_Customerrecord pc ";
        $sql .= "WHERE pc.toplimit > pc.finish AND pc.`status` = 1 ";
        $sql .= "AND pc.team = " . get_channel($salecount_res['channel']) . " ";
        if (($isTmallTeach_qj === 0) && ($isTmallTeach_zy === 0)) {
            $sql .= " AND pc.cShop = 1 ";
        } else {
            if ($isTmallTeach_qj == 1) {//旗舰 指定分配
                $sql .= " AND pc.tmallReception_qj = 1 ";
            }
            if ($isTmallTeach_zy == 1) {//专营店 指定分配
                $sql .= " AND pc.tmallReception_zy = 1 ";
//                $sql .= " AND pc.team = 2 "; //指定分配给360
            }
//            else {
//                $sql .= "AND pc.team = " . get_channel($salecount_res['channel']) . " ";
//            }
        }
        if ($isQQTeach == 1) {//QQ教学 指定分配
            $sql .= " AND pc.qqReception = 1 ";
        }
        $sql .= " ORDER BY pc.lastDistribution ASC ";
        $customerres = Model::query_list($db, $customer, $sql);
        if (!$customerres[0]) die_error(USER_ERROR, '真的没有售后了~');
        $models = Model::list_to_array($customerres['models']);
        if ($customerres['count'] != 0) {
//            $salecount_result = $salecount->load($db, $salecount);
//            if (!$salecount_result[0]) die_error(USER_ERROR, '重新分配失败,请稍后重试~');
            $conflictWith = $salecount_res['conflictWith'];
            if ($conflictWith != 0) {
                $salecount->reset();
                $salecount->set_where_and(p_salecount::$field_conflictWith, SqlOperator::Equals, $conflictWith);
                $salecount_list_results = Model::query_list($db, $salecount);
                if (!$salecount_list_results[0]) die_error(USER_ERROR, '重新分配失败,请稍后重试~');
                if ($salecount_list_results['count'] == 1) {
                    $change_status_sql = "update p_salecount SET STATUS = 1 WHERE id =" . $conflictWith;
                }
            }
            $user = $models[0];
            $userid = $user['userid'];
            $username = $user['username'];
            $nickName = $user['nickname'];
            $team = $user['team'];
            $group = $user['group'];
            $toplimit = $user['toplimit'];
            $finish = $user['finish'];
            $salecount->reset();
            $salecount->set_id($id);
            $salecount->set_customer_id($userid);
            $salecount->set_customer($username);
            $salecount->set_nick_name($nickName);
            $salecount->set_customer_date('now');
            if ($status == 0) {
                $salecount->set_status(1);
            }
            $salecount->set_conflictWith(0);
            $salecount->set_scheduledPackage(0);
            $fill = new P_Fills();
            $fill->set_customer_id($userid);
            $fill->set_nick_name($nickName);
            $fill->set_customer($username);
            $fill->set_where_and(P_Fills::$field_sale_id, SqlOperator::Equals, $id);

            $update_distribution_sql = "update P_Customerrecord SET `finish` = `finish` + 1, lastDistribution = '" . (microtime(TRUE) * 10000) . "' where id = " . $user['id'] . ' AND `finish` < `toplimit`';
            pdo_transaction($db, function($db) use($salecount, $fill, $update_distribution_sql, $change_status_sql) {
                if ($update_distribution_sql != '') {
                    $cresult = Model::execute_custom_sql($db, $update_distribution_sql);
                    if (!$cresult[0]) throw new TransactionException(PDO_ERROR_CODE, '重新分配失败~' . $cresult['detail_cn'], $cresult);
                }
                if ($change_status_sql != '') {
                    $csresult = Model::execute_custom_sql($db, $change_status_sql);
                    if (!$csresult[0]) throw new TransactionException(PDO_ERROR_CODE, '重新分配失败~' . $cresult['detail_cn'], $cresult);
                }
                $result = $salecount->update($db);
                if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '重新分配失败~' . $result['detail_cn'], $result);
                $result0 = $fill->update($db, true);
                if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '重新分配失败~' . $result0['detail_cn'], $result0);
            });

            //$isupdate = is_today($date)?1:0;
            $isupdate = 1;
            $date = 0;
            $action = 0;
            $ind_refund_rate = 0;
            update_customerstatistics($db, $userid, $money, $action, $date, $ind_refund_rate, $isupdate);
            echo_msg('分配成功');
        }else {
            die_error(USER_ERROR, '真的没有售后了~');
        }
    }
    if ($action == 5) {
        $id = request_int('sale_id');
        $sql = "update p_salecount ps SET ps.praise = ps.praise+1 WHERE ps.id=" . $id;
        $db = create_pdo();
        $result = Model::execute_custom_sql($db, $sql);
        if (!$result[0]) die_error(USER_ERROR, "点赞失败,请稍后重试~");
        echo_msg("点赞成功~");
    }

    /**
     * 添加客户说明
     */
    if ($action == 6) {
        $ce = request_string("cuex");
        $cid = request_int("cuid");
        $salecount = new p_salecount();
        $salecount->set_customer_explain($ce);
        $salecount->set_where_and(p_salecount::$field_id, SqlOperator::Equals, $cid);
        $db = create_pdo();
        $result = $salecount->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, "添加客户详细说明失败~");
        echo_result(array('code' => 0, 'msg' => "添加客户详细说明成功~"));
    }
});
function get_channel_new($channel) {
    $channel_array = array(
        '百度(PC端)' => 0,
        '百度(YD端)' => 0,
        '百度直通车' => 0,
        '360' => 1,
        '搜狗' => 1,
        '360直通车' => 1,
        '搜狗直通车' => 1
    );
    return $channel_array[$channel];
}

function get_channel($channel) {
    $channel_array = array(
        '百度(PC端)' => 1,
        '百度(YD端)' => 1,
        '百度直通车' => 1,
        '360' => 2,
        '搜狗' => 2,
        '360直通车' => 2,
        '搜狗直通车' => 2
    );
    return $channel_array[$channel];
}

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

function get_group1($group_id) {
    $group_array = array(
        701 => '百度',
        703 => '百度',
        705 => '百度',
        707 => '百度',
        713 => '百度',
        716 => '百度',
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

function get_group_by_channel($channel) {
    if (str_equals($channel, '百度直通车') || str_equals($channel, '百度')) {
        return '百度(PC)';
    } else {
        $channel = str_replace('端', '', $channel);
        $channel = str_replace('直通车', '', $channel);
        return $channel;
    }
}

function get_group_by_channel1($channel) {
    $group_array = array('百度', '360', '搜狗');
    foreach ($group_array as $value) {
        if (strpos($channel, $value) !== false) {
            return $value;
        }
    }
}

function get_push_array($group_id, $db, $use_redis, $redis_config, $threshold) {
    /**
     * 当前售前当日接单数
     */
    $retArray = array('Saler' => '', 'SalerTotals' => '', 'TodayTotals' => 0, 'TodayBaiduTotals' => 0, 'Today360Totals' => 0, 'TodaySogouTotals' => 0, 'FirstSaler' => '', 'FirstTotals' => '', 'SecondSaler' => '', 'SecondTotals' => '', 'ThirdSaler' => '', 'ThirdTotals' => '', 'Code' => 0, 'Msg' => '', 'Remark' => '', 'MsgType' => 1);
    $userSalecountSql = "SELECT ps.presales AS Saler, COUNT(ps.presales_id) AS SalerTotals FROM p_salecount ps WHERE " . getWhereSql("ps") . " AND ps.presales_id = " . $group_id . " GROUP BY ps.presales_id";
    $userSalecountResult = Model::execute_custom_sql($db, $userSalecountSql);
    $userSalecountModel = $userSalecountResult['results'][0];
    $retArray['Saler'] = $userSalecountModel['Saler'];
    $retArray['SalerTotals'] = $userSalecountModel['SalerTotals'];

    $redis = new Redis();
    $redis_connected = $redis->connect($redis_config['host'], $redis_config['port']);
    if ($use_redis) {
        $use_redis = $redis_connected;
        if ($redis_connected === true) {
            $date_adjusted_format = adjust_time('Y_m_d', $threshold) . '_';
            $retArray['TodayTotals'] = (int) $redis->get($date_adjusted_format . 'TodayTotals');
            $retArray['TodayBaiduTotals'] = (int) $redis->get($date_adjusted_format . 'TodayBaiduPCTotals') + (int) $redis->get($date_adjusted_format . 'TodayBaiduMTotals');
            $retArray['Today360Totals'] = (int) $redis->get($date_adjusted_format . 'Today360Totals');
            $retArray['TodaySogouTotals'] = (int) $redis->get($date_adjusted_format . 'TodaySogouTotals');
        }
    }
    if (!$use_redis) {
        $retArray['TodayTotals'] = 0;
        $retArray['TodayBaiduTotals'] = 0;
        $retArray['Today360Totals'] = 0;
        $retArray['TodaySogouTotals'] = 0;

        /**
         * 当日总单数
         */
        $sumCountSql = "SELECT COUNT(*) AS TodayTotals FROM p_salecount ps WHERE " . getWhereSql("ps");
        $sumCountResult = Model::execute_custom_sql($db, $sumCountSql);
        $sumCountModel = $sumCountResult['results'][0];
        $retArray['TodayTotals'] = $sumCountModel['TodayTotals'];
        /**
         * 各渠道销售单数
         */
        $groupSumCountSql = "SELECT ps.channel, COUNT(ps.channel) AS count FROM p_salecount ps WHERE " . getWhereSql("ps") . " GROUP BY ps.channel";
        $groupSumCountResult = Model::execute_custom_sql($db, $groupSumCountSql);
        $sumCountModel = $groupSumCountResult['results'];
        foreach ($sumCountModel as $model) {
            $count = isset($model['count']) ? (int) $model['count'] : 0;
            //$grout_id = $model['group_id'];
            //$group_name = get_group1($grout_id);
            $group_name = get_group_by_channel1($model['channel']);
            if (str_equals($group_name, "百度")) {
                $retArray['TodayBaiduTotals'] += $count;
            } else if (str_equals($group_name, "360")) {
                $retArray['Today360Totals'] += $count;
            } else if (str_equals($group_name, "搜狗")) {
                $retArray['TodaySogouTotals'] += $count;
            }
        }
    }
    /**
     * 销售单数前三
     */
    $top3CountSql = "SELECT ps2.presales AS Saler, ps2.group_id AS `Group`, count(ps2.presales_id) AS Count FROM p_salecount AS ps2 WHERE " . getWhereSql("ps2") . " GROUP BY ps2.presales_id ORDER BY COUNT(ps2.presales_id) DESC LIMIT 3";
    $top3CountResult = Model::execute_custom_sql($db, $top3CountSql);
    $top3CountModel = $top3CountResult['results'];
    foreach ($top3CountModel as $id => $model) {
        $Saler = isset($model['Saler']) ? $model['Saler'] . '(' . get_group1($model['Group']) . ')' : '';
        switch ($id) {
            case 0:
                $retArray['FirstSaler'] = $Saler;
                $retArray['FirstTotals'] = isset($model['Count']) ? $model['Count'] : '';
                break;
            case 1:
                $retArray['SecondSaler'] = $Saler;
                $retArray['SecondTotals'] = isset($model['Count']) ? $model['Count'] : '';
                break;
            case 2:
                $retArray['ThirdSaler'] = $Saler;
                $retArray['ThirdTotals'] = isset($model['Count']) ? $model['Count'] : '';
                break;
        }
    }
    return $retArray;
}

function refresh_sale_statistics($incr_or_decr = 'incr', $group_id, $salecountData, $redis_config, $threshold) {
    $redis = new Redis();
    $redis_connected = $redis->connect($redis_config['host'], $redis_config['port']);
    if ($redis_connected === true) {
        $date_adjusted_format = adjust_time('Y_m_d', $threshold);
        $is_today = str_equals($date_adjusted_format, date('Y_m_d', strtotime($salecountData->addtime)));
        $date_adjusted_format .= '_';
        $sale_statistics_array = array(
            '百度(PC)' => 'TodayBaiduPCTotals',
            '百度(YD)' => 'TodayBaiduMTotals',
            '360' => 'Today360Totals',
            '搜狗' => 'TodaySogouTotals'
        );
        $sale_statistics_timely_array = array(
            '百度(PC)' => 'TodayBaiduPCTimelyTotals',
            '百度(YD)' => 'TodayBaiduMTimelyTotals',
            '360' => 'Today360TimelyTotals',
            '搜狗' => 'TodaySogouTimelyTotals'
        );
        $group_name = get_group_by_channel($salecountData->channel);
        if (str_length($group_name) <= 0) $group_name = get_group($group_id);
        $sale_statistics_key = $sale_statistics_array[$group_name];
        if (str_equals($incr_or_decr, 'decr')) {
            if ($is_today && $redis->get($date_adjusted_format . $sale_statistics_key) > 0) {
                $redis->decr($date_adjusted_format . $sale_statistics_key);
                $redis->decr($date_adjusted_format . 'TodayTotals');
            }
        } else {
            $redis->incr($date_adjusted_format . $sale_statistics_key);
            $redis->incr($date_adjusted_format . 'TodayTotals');
        }
        if ($salecountData->isTimely == 1) {
            $sale_statistics_timely_key = $sale_statistics_timely_array[$group_name];
            if (str_equals($incr_or_decr, 'decr')) {
                if ($is_today && $redis->get($date_adjusted_format . $sale_statistics_timely_key) > 0) {
                    $redis->decr($date_adjusted_format . $sale_statistics_timely_key);
                    $redis->decr($date_adjusted_format . 'TodayTotalsTimely');
                }
            } else {
                $redis->incr($date_adjusted_format . $sale_statistics_timely_key);
                $redis->incr($date_adjusted_format . 'TodayTotalsTimely');
            }
        }
    }
}
