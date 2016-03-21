<?php

use Models\Base\Model;

require '../application.php';
require '../loader-api.php';

$key = request_md5_32('key');
if (strcmp($key, BACKEND_KEY) !== 0) die_error(USER_ERROR, '密钥错误');

set_time_limit(0);

/* * *********这里要谨慎，一定是测试库哦********** */
$db = create_pdo_test_db();
/* * *********这里要谨慎，一定是测试库哦********** */

$result = Model::execute($db, 'SHOW TABLE STATUS');  // 'SHOW TABLE STATUS' | 'SHOW TABLES'
if (!$result) die_pdo_error(PDO_ERROR_CODE, '查询数据表时出错', $result);
if ($result['count'] === 0) die_error(USER_ERROR, '未能查询到数据库表信息');
$tables = $result['results'];
$tables = array_column($tables, 'Name');
foreach ($tables as $table_name) {
    //$table_name = $table['Name'];
    $result = Model::execute($db, "SHOW COLUMNS FROM $table_name");
    if (!$result) die_pdo_error(PDO_ERROR_CODE, '查询表结构时出错', $result);
    if ($result['count'] === 0) die_error(USER_ERROR, '未能查询到表中的列信息');
    $columns = $result['results'];
    $columns = array_column($columns, 'Field');

    //更新用户表
    if (str_equals($table_name, 'm_user')) {
        $sql = "UPDATE `m_user` SET `idcard` = '100000000000000000', `phone`='18888888888', `qq`='888888888',`emergency_phone` = '18888888888',`address`='中国.四川.成都'";
        Model::execute_custom_sql($db, $sql);
    }

    //更新username字段
    if (in_array('username', $columns)) {
        $sql = "UPDATE `$table_name` SET `username` = (SELECT `username` FROM `m_user` WHERE `$table_name`.userid = `m_user`.userid LIMIT 1)";
        Model::execute_custom_sql($db, $sql);
    }

    //更新phone字段
    if (in_array('phone', $columns)) {
        $sql = "UPDATE `$table_name` SET `phone` = CONCAT('188',SUBSTR(RAND(),3,8))";
        Model::execute_custom_sql($db, $sql);
    }

    //更新mobile字段
    if (in_array('mobile', $columns)) {
        $sql = "UPDATE `$table_name` SET `mobile` = CONCAT('188',SUBSTR(RAND(),3,8))";
        Model::execute_custom_sql($db, $sql);
    }

    //更新销售相关表
    if (strpos($table_name, 'p_') !== false) {
        $sql = "UPDATE `$table_name` SET {$columns[0]} = {$columns[0]} ";
        if (in_array('ww', $columns)) {
            $sql.=", `ww` = CONCAT('测试旺旺',SUBSTR(RAND(),3,6))";
        }
        if (in_array('name', $columns)) {
            $sql.=", `name` = CONCAT('测试姓名',SUBSTR(RAND(),3,6))";
        }
        if (in_array('qq', $columns)) {
            $sql.=", `qq` = SUBSTR(RAND(),3,9)";
        }
        if (in_array('mobile', $columns)) {
            $sql.=", `mobile` = CONCAT('188',SUBSTR(RAND(),3,8))";
        }
        if (in_array('alipay_account', $columns)) {
            $sql.=", `alipay_account` = CONCAT('188',SUBSTR(RAND(),3,8))";
        }
        Model::execute_custom_sql($db, $sql);
    }
}

function create_pdo_test_db() {
    $pdo_dsn = 'mysql:host=' . '121.40.50.61' . ';port=' . '3306' . ';dbname=' . 'yc_oa_test_20160118';
    $pdo_options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'/* ,
              PDO::ATTR_PERSISTENT => true */
    );
    try {
        $pdo = new PDO($pdo_dsn, 'ycadmin', 'f98001ce038deb37', $pdo_options);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        return $pdo;
    } catch (PDOException $ex) {
        die_error(PDO_ERROR_CODE, PDO_CREATE_ERROR_MSG);
    }
}
