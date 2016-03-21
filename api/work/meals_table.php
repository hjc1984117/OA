<?php

/**
 * 用餐签出,签到
 *
 * @author B.Maru
 * @copyright 2015 星密码
 * @version 2015/11/23
 */
use Models\Base\Model;
use Models\w_mealstable;
use Models\Base\SqlOperator;

require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 1) {
        $userid = request_login_userid();
        $employees = get_employees();
        $date = request_string('date');
        $dept = request_int('dept');
        if (!isset($date)) {
            $date = date('Y-m');
        } else {
            $date = date('Y-') . $date;
        }
        if (!isset($dept)) {
            $employee = $employees[$userid];
            $dept = $employee['dept1_id'];
        }
        $sql = "SELECT wm.username AS username, IF(ISNULL(wm.add_date),'" . $date . "',DATE_FORMAT(wm.add_date,'%Y-%m')) date,";
        $sql.="wm.dept,CONCAT('{',GROUP_CONCAT(CONCAT(CONCAT('\"m',CAST(DATE_FORMAT(wm.add_date,'%d') AS SIGNED)),'\":','{',CONCAT('\"ma\":',wm.ma,',\"mn\":',wm.mn),'}')),'}') AS m ";
        $sql.="FROM (SELECT u.userid AS username,w.add_date,u.dept1_id AS dept,w.ma,w.mn FROM m_user u LEFT JOIN (";
        $sql.="SELECT m.userid AS userid,m.dept_id AS dept,m.add_date AS add_date,";
        $sql.="(ceil(UNIX_TIMESTAMP(IF(ISNULL(m.ma_in),0,m.ma_in))-UNIX_TIMESTAMP(IF(ISNULL(m.ma_out),0,m.ma_out)))) AS ma,";
        $sql.="(ceil(UNIX_TIMESTAMP(IF(ISNULL(m.mn_in),0,m.mn_in))-UNIX_TIMESTAMP(IF(ISNULL(m.mn_out),0,m.mn_out)))) AS mn ";
        $sql.="FROM w_mealstable m WHERE DATE_FORMAT(m.add_date,'%Y-%m') = '" . $date . "'";
        $sql.=") w ON w.userid = u.userid WHERE u.`status` IN (1,2) AND u.userid != 1 AND u.dept1_id = " . $dept . "";
        $sql.=") wm GROUP BY wm.username;";

        $db = create_pdo();
        $result = Model::execute_custom_sql($db, $sql);
        $depts = get_depts();
        $model = $result['results'];
        array_walk($model, function(&$d) use($employees, $depts) {
            $employee = $employees[$d['username']];
            $dept_id = $employee['dept1_id'];
            $dept = $depts[$dept_id];
            $d['username'] = $employee['username'];
            $d['dept'] = $dept['text'];
            $obj = json_decode($d['m'], true);
            for ($index = 1; $index <= 31; $index++) {
                $m = $obj['m' . $index];
                if (isset($m)) {
                    $ma = $m['ma'];
                    $mn = $m['mn'];
                    if ($ma < 0) {//未签到
                        $d['ma' . $index] = "吃";
                        $d['mac' . $index] = "ain";
                    } else if ($ma == 0) {
                        $d['ma' . $index] = "-";
                        $d['mac' . $index] = "";
                    } else {
                        if ($ma > 3600) {
                            $d['ma' . $index] = "X";
                            $d['mac' . $index] = "x";
                        } else {
                            $d['ma' . $index] = "Y";
                            $d['mac' . $index] = "";
                        }
                    }
                    if ($mn < 0) {
                        $d['mn' . $index] = "吃";
                        $d['mnc' . $index] = "nin";
                    } else if ($mn == 0) {
                        $d['mn' . $index] = "-";
                        $d['mnc' . $index] = "";
                    } else {
                        if ($mn > 3600) {
                            $d['mn' . $index] = "X";
                            $d['mnc' . $index] = "x";
                        } else {
                            $d['mn' . $index] = "Y";
                            $d['mnc' . $index] = "";
                        }
                    }
                } else {
                    $d['ma' . $index] = "-";
                    $d['mn' . $index] = "-";
                    $d['mac' . $index] = "";
                    $d['mnc' . $index] = "";
                }
            }
            unset($d['m']);
        });
        echo_result(array('list' => $model));
    }
});
