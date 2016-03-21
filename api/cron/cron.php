<?php

/**
 * 开放API接口
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/8/17
 */
use Models\Base\Model;
use Models\S_ExpireReminder;

require '../../common/http.php';
require '../../application.php';
require '../../loader-api.php';

$action = request_action();
list($time, $sign) = filter_request(array(
    request_datetime('time'),
    request_md5_32('sign')));
$sign_str = $action . $time . PUBLIC_KEY;
if (!str_equals($sign, md5($sign_str))) die_error(USER_ERROR, 'Error Signature');
if (abs(time() - strtotime($time)) > 300) die_error(USER_ERROR, 'Invalid Timestamp');

execute_request(HttpRequestMethod::Get, function() use($action) {
    /**
     * 到期提醒
     */
    if ($action == 1) {
        $expireReminder = new S_ExpireReminder();
        $expireReminder->set_custom_where(" AND UNIX_TIMESTAMP(dueDate) - UNIX_TIMESTAMP(NOW()) <= 10*24*3600 ");
        $db = create_pdo();
        $result = Model::query_list($db, $expireReminder);
        if (!$result[0]) die;
        $models = Model::list_to_array($result['models'], array(), function(&$d) {
                    $d['countdown'] = timediff(($d['dueDate']) . ' 23:59:59', date("Y-m-d H:i:s"));
                });
        echo_result($models);
    }
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    //for 星密码开发者工具箱，域名解析，同步IP到OA
    if ($action == 1) {
        $domainIpStr = request_string('diStr');
        $domainIpArray = json_decode($domainIpStr, true);
        $sql = '';
        foreach ($domainIpArray as $value) {
            $sql.="UPDATE S_Doamin SET `ipAddress`='$value[i]' WHERE `name`='$value[d]';";
        }
        $db = create_pdo();
        $result = Model::execute_custom_sql($db, $sql);
        if (!$result[0]) die_error(USER_ERROR, 'Failed');
        echo_msg('Success');
    }

    //暂未使用
    //重置星密码销售统计计数(redis)
    if ($action == 2) {
        $redis = new Redis();
        $redis->pconnect('127.0.0.1', 6380);
        $salecount_array = array('TodayBaiduPCTotals', 'TodayBaiduPCTimelyTotals', 'TodayBaiduMTotals', 'TodayBaiduMTimelyTotals', 'Today360Totals', 'Today360TimelyTotals', 'TodaySogouTotals', 'TodaySogouTimelyTotals');
        $yesterday_adjusted = get_yesterday(adjust_time('Y_m_d', '03:00:00'));
        $salecount_array = array_map(function($key) use($yesterday_adjusted) {
            return $yesterday_adjusted . '-' . $key;
        }, $salecount_array);
        $redis->delete($salecount_array);
        $redis->close();
    }
});

function timediff($date1, $date2) {
    $date1 = strtotime($date1);
    $date2 = strtotime($date2);
    if ($date1 <= $date2) {
        return "已过期";
    }
    $mins = ceil(($date1 - $date2) / 60);
    if ($mins > (60 * 24)) {
        return floor(($mins / (60 * 24))) . "天" . floor(($mins % (60 * 24)) / 60) . "小时" . (($mins % (60 * 24)) % 60) . "分";
    } else {
        return floor(($mins % (60 * 24)) / 60) . "小时" . (($mins % (60 * 24)) % 60) . "分";
    }
}
