<?php

use Models\Base\Model;

require '../../application.php';
require '../../loader-api.php';

execute_request(HttpRequestMethod::Post, function() {
    $attdata = strtoupper(md5("283F42764DA6DBA2522412916B031080attdata" . date("Ymd")));
    $sqldata = request_string("attdata" . date('Ymd'));
    $z = request_string("z");
    $sql_array = array();
    if (str_equals($attdata, $z)) {
        if (isset($sqldata)) {
            $sqldata = explode("_", $sqldata);
            $array_1 = explode(",,", $sqldata[0]);
            foreach ($array_1 as $k => $v) {
                $data_array_1 = explode(",", $v);
                if (count($data_array_1) > 1) {
                    $insert_sql = "INSERT INTO w_checklist (atid,checktime) values (" . $data_array_1[0] . "," . $data_array_1[1] . ");";
                    array_push($sql_array, $insert_sql);
                }
            }
            $drop_sql = "DROP TEMPORARY TABLE IF EXISTS tmp_atuser;";
            array_push($sql_array, $drop_sql);

            $array_2 = explode(",,", $sqldata[1]);
            $create_sql = "";
            foreach ($array_2 as $k => $v) {
                $data_array_2 = explode(",", $v);
                if ($k == 0) {
                    $create_sql.= "CREATE TEMPORARY TABLE IF NOT EXISTS tmp_atuser ENGINE = MEMORY DEFAULT CHARSET=UTF8 select " . $data_array_2[0] . " atid," . $data_array_2[1] . " bid,'" . $data_array_2[2] . "' aname ";
                } else {
                    if (count($data_array_2) > 1) {
                        $create_sql .= " union all select " . $data_array_2[0] . "," . $data_array_2[1] . ",'" . $data_array_2[2] . "' ";
                    }
                }
            }
            $create_sql.=";";
            array_push($sql_array, $create_sql);
            $ins_w_sql = "INSERT INTO w_attuser(atid,bid,aname) SELECT atid,bid,aname FROM tmp_atuser WHERE atid not in (select atid from w_attuser);";
            array_push($sql_array, $ins_w_sql);
            $update_sql = "UPDATE w_attuser join tmp_atuser on w_attuser.atid=tmp_atuser.atid set w_attuser.bid=tmp_atuser.bid,w_attuser.aname=tmp_atuser.aname ;";
            array_push($sql_array, $update_sql);
            $call_sql = "call up_sync_check;";
            array_push($sql_array, $call_sql);

            $error_sql = "";
            $db = create_pdo();
            foreach ($sql_array as $sql) {
                $res = Model::execute_custom_sql($db, $sql);
                if (!$res[0]) {
                    $detail = $res['detail'][2];
                    if (!strstr($detail, 'PRIMARY')) {
                        $error_sql.=$sql . "\r\n";
                    }
                }
            }
            if (!str_equals($error_sql, "")) {
                $file_dir = "log/error_" . date("YmdHis") . ".sql";
                $file = fopen($file_dir, "w") or die("Unable to open file!");
                fwrite($file, $error_sql);
                fclose($file);
            }
            echo '0';
            exit();
        } else {
            echo '-100';
            exit();
        }
    } else {
        echo '-999';
        exit();
    }
});
