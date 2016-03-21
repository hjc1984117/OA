<?php

use Models\Base\Model;

require '../application.php';
require '../loader-api.php';

$key = request_md5_32('key');
if (strcmp($key, BACKEND_KEY) !== 0) die_error(USER_ERROR, '密钥错误');

/*
 * 表名批量改大写开头
 */
//$sql = 'SHOW TABLES';
//$db = create_pdo();
//$result = Model::execute_custom_sql($db, $sql);
//foreach ($result['results'] as $value) {
//    $sql = 'ALTER TABLE ' . $value['Tables_in_yc_oa'] . ' RENAME TO ' . strtoupper(substr($value['Tables_in_yc_oa'], 1, 3)) . substr($value['Tables_in_yc_oa'], 4);
//    $result = Model::execute_custom_sql($db, $sql);
//}
//die;

$pdo_dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . 'information_schema';
$pdo_options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
);
try {
    $pdo = new PDO($pdo_dsn, DB_USR, DB_PWD, $pdo_options);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
    //return $pdo;
} catch (PDOException $e) {
    die_error(PDO_ERROR_CODE, PDO_CREATE_ERROR_MSG);
}
$db = $pdo;
$result = Model::execute($db, "SELECT TABLE_NAME, COLUMN_NAME, COLUMN_TYPE, COLUMN_DEFAULT, IS_NULLABLE, COLUMN_KEY, EXTRA, COLUMN_COMMENT FROM `COLUMNS` WHERE TABLE_SCHEMA = '".DB_NAME."';");
if (!$result) die_pdo_error(PDO_ERROR_CODE, '查询数据表时出错', $result);
if ($result['count'] === 0) die_error(USER_ERROR, '未能查询到数据库表信息');
$table_schemas = $result['results'];

$db = create_pdo();
$result = Model::execute($db, "SHOW TABLE STATUS;");
if (!$result) die_pdo_error(PDO_ERROR_CODE, '查询数据表时出错', $result);
if ($result['count'] === 0) die_error(USER_ERROR, '未能查询到数据库表信息');
$tables = $result['results'];

array_walk($tables, function(&$table) use($table_schemas) {
    $table['Schema'] = array_filter($table_schemas, function($table_schema) use($table) {
        return strcasecmp($table_schema['TABLE_NAME'], $table['Name']) === 0;
    });
});
?>
<html>
    <head>
        <meta charset="UTF-8">
        <style>
            body{margin: 0px;padding: 0px;}
            th,td{margin: 0px;padding: 0px;text-align: center;width: 200px;height: 40px;line-height: 40px;}
            table{width: 50%; border: solid 1px #cecece;margin-top: 20px;padding: 0px;border-spacing: 0;border-collapse: collapse;margin-left: 20%;}
        </style>
    </head>
    <body>
        <div style="position:fixed;z-index:999;top:200px;right:300px;width:200px;height:21px;background: yellow;">
            快速定位
            <select onchange="javascript:window.location = '#' + this.value">
                <?php
                foreach ($tables as $table) {
                    echo "<option value='" . $table['Name'] . "'>" . $table["Comment"] . "</option>";
                }
                ?>
            </select>
        </div>
        <?php foreach ($tables as $table) { ?>
            <table align="center;" border="1" id="<?php echo $table['Name'] ?>">
                <tr>
                    <th colspan="3" align="left"><?php echo $table['Name'] . ' ' . $table['Comment'] ?></th>
                </tr>
                <?php foreach ($table['Schema'] as $schema) { ?>
                    <tr>
                        <th><?php echo $schema['COLUMN_NAME'] ?></th>
                        <td><?php echo $schema['COLUMN_TYPE'] ?></td>
                        <td><?php echo $schema['COLUMN_COMMENT'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </body>
</html>