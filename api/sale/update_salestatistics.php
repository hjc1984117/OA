<?php

use Models\Base\Model;
use Models\Base\SqlOperator;
use Models\p_salestatistics;
use Models\M_User;
use Models\P_Fills;
use Models\P_Upgrade;

require_once '../../common/http.php';

function update_salestatistics($db, $presales_id = 0, $presales = '', $sendMsg = false) {
    if ($presales_id == 0) return;
    $searchTime = date("Y-m-d");
    $h = (int) date("H");
    if ($h < 3) {
        $searchTime = date('Y-m-d', strtotime("-1 day"));
    }
    /**
     * 查询QQ接入
     */
    $sql = "SELECT qq_num,access_time FROM p_qqaccess WHERE presales_id = " . $presales_id;
    $h = (int) date("H");
    if ($h < 3) {
        $sql.=" AND '" . date('Y-m-d', strtotime("-1 day")) . " 03:00:00' <=  DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') <= '" . date('Y-m-d') . " 02:59:59' ";
    } else {
        $sql.=" AND '" . date('Y-m-d') . " 03:00:00' <=  DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') <= '" . date('Y-m-d', strtotime("+1 day")) . " 02:59:59' ";
    }
    $sql .= " ORDER BY addtime ASC";
    $into_count = 0;
    $label = "QQ号";
    $num = 0;
    $qqaccess = Model::execute_custom_sql($db, $sql);
    array_walk($qqaccess['results'], function($d) use(&$into_count, &$num) {
        $into_count++;
        if ($d['access_time'] === null) {
            $num++;
        }
    });
    /**
     * 查询销售统计
     */
    $sql = "select isTimely,money,channel FROM p_salecount WHERE presales_id = " . $presales_id . " ";
    if ($h < 3) {
        $sql.=" AND '" . date('Y-m-d', strtotime("-1 day")) . " 03:00:00' <=  DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') <= '" . date('Y-m-d') . " 02:59:59' ";
    } else {
        $sql.=" AND '" . date('Y-m-d') . " 03:00:00' <=  DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(addtime,'%Y-%m-%d %H:%i:%s') <= '" . date('Y-m-d', strtotime("+1 day")) . " 02:59:59' ";
    }
    $sql.="ORDER BY addtime ASC";
    $timely = Model::execute_custom_sql($db, $sql);
    $deal_count = 0;
    $timely_count = 0;
    $amount = 0;
    $commission = 0;
    $channel = "";
    array_walk($timely['results'], function($d) use(&$deal_count, &$timely_count, &$channel, &$amount, &$commission) {
        $deal_count++;
        $amount+=$d['money'];
        if (str_equals($d['isTimely'], '1')) {
            $timely_count++;
        }
        if (str_equals($channel, "")) {
            $channel = get_channel_($d['channel']);
        }
    });
    /**
     * 获取当前人员销售等级
     */
    $user = new M_User();
    $user->set_query_fields(array('salecount_lv'));
    $user->set_where_and(M_User::$field_userid, SqlOperator::Equals, $presales_id);
    $result_user = $user->load($db, $user);
    $salecount_lv = "";
    if ($result_user[0]) {
        $salecount_lv = $user->get_salecount_lv();
    }

    /**
     * 补欠款/升级
     */
    $fu_sql = "SELECT IFNULL(SUM(sum),0) AS sum FROM ( ";
    $fu_sql .="SELECT * FROM (SELECT SUM(fill_sum) AS sum  FROM p_fills WHERE add_name_id = " . $presales_id . " AND DATE_FORMAT(add_time,'%Y-%m-%d') = '" . $searchTime . "') a ";
    $fu_sql .="UNION ALL ";
    $fu_sql .="SELECT * FROM (SELECT SUM(upgrade_sum) AS sum FROM p_upgrade WHERE add_name_id = " . $presales_id . "  AND DATE_FORMAT(add_time,'%Y-%m-%d') = '" . $searchTime . "' )b ";
    $fu_sql .=") a ";
    $fu_res = Model::execute_custom_sql($db, $fu_sql);
    array_walk($fu_res['results'], function($d) use (&$amount) {
        $amount+=$d['sum'];
    });
    $commission += get_commission($amount, $salecount_lv);
    $sales = new p_salestatistics();
    $sales->set_where_and(p_salestatistics::$field_userid, SqlOperator::Equals, $presales_id);
    $sales->set_custom_where(" AND DATE_FORMAT(reltime,'%Y-%m-%d') = '" . $searchTime . "' ");
    $result = $sales->load($db, $sales);
    $data_array = array(
        'channel' => $channel,
        'salecount_lv' => $salecount_lv,
        'into_count' => $into_count,
        'deal_count' => $deal_count,
        'timely_count' => $timely_count,
        'amount' => $amount,
        'commission' => $commission,
        key_names => array(
            'channel' => '渠道',
            'salecount_lv' => '提成等级',
            'into_count' => '转入数',
            'accept_count' => '接入数',
            'deal_count' => '成交数',
            'timely_count' => '及时数',
            'amount' => '金额',
            'commission' => '提成'
        )
    );
    if ($result[0]) {
        $sales->set_into_count($into_count);
        $sales->set_deal_count($deal_count);
        $sales->set_timely_count($timely_count);
        $sales->set_amount($amount);
        $sales->set_commission($commission);
        add_data_change_log($db, $data_array, $sales, 13);
        $sales->update($db);
    } else {
        $group = get_channel_($channel);
        $sales->set_channel($group);
        $sales->set_salecount_lv($salecount_lv);
        $sales->set_into_count($into_count);
        $sales->set_deal_count($deal_count);
        $sales->set_timely_count($timely_count);
        $sales->set_amount($amount);
        $sales->set_commission($commission);
        $sales->set_userid($presales_id);
        $sales->set_username($presales);
        $sales->set_reltime($searchTime);
        $sales->set_addtime('now');
        $sales->insert($db);
        add_data_add_log($db, $data_array, $sales, 13);
    }
    if ($sendMsg) {
        $title = "新Q接入提醒";
        $text = "亲，您有" . $num . "条转Q消息未处理，请尽快加人确认。";
        $msg = array('step' => 1, 'title' => $title, 'addtime' => date("Y-m-d H:i:s"), 'username' => request_login_username(), 'caption' => $label, 'text' => $text, 'num' => $num, 'Code' => 0, 'Msg' => '', 'Remark' => '', 'MsgType' => 3);
        send_push_msg(json_encode($msg), $presales_id);
    }
}

function get_channel_($group_id) {
    $channel_array = array(
        "百度(PC端)" => "百度(PC端)",
        "百度(YD端)" => "百度(YD端)",
        "360" => "360",
        "搜狗" => "搜狗",
        "百度直通车" => "百度(PC端)",
        "360直通车" => "360",
        "搜狗直通车" => "搜狗",
        "神马" => "UC神马"
    );
    return $channel_array[$group_id];
}
