<?php

use Models\Base\Model;

require '../application.php';
require '../loader-api.php';

$key = request_md5_32('key');
if (strcmp($key, BACKEND_KEY) !== 0) die_error(USER_ERROR, '密钥错误');

header("Content-Type:text/html; charset:utf-8");
$document_root = $_SERVER['DOCUMENT_ROOT'];

$db = create_pdo();

$result = Model::execute($db, 'SHOW TABLE STATUS');  // 'SHOW TABLE STATUS' | 'SHOW TABLES'
if (!$result) die_pdo_error(PDO_ERROR_CODE, '查询数据表时出错', $result);
if ($result['count'] === 0) die_error(USER_ERROR, '未能查询到数据库表信息');
$tables = $result['results'];
foreach ($tables as $table) {

    $table_name = $table['Name'];
    $table_comment = $table['Comment'];
    $model_name = $table_name;

    //if (strlen($model_name) <= 0) $model_name = $table_name;
    $model_code_content = '';

    $result = Model::execute($db, "SHOW COLUMNS FROM $table_name");
    if (!$result) die_pdo_error(PDO_ERROR_CODE, '查询表结构时出错', $result);
    if ($result['count'] === 0) die_error(USER_ERROR, '未能查询到表中的列信息');
    $columns = $result['results'];

    $model_code_content.= "<?php\r\n\r\n";
    $model_code_content.="/**\r\n* $table_comment\r\n*\r\n* @author 自动生成的实体类\r\n* @copyright (c) 2015, 星密码集团\r\n* @version 1.0\r\n*/\r\n\r\n";
    $model_code_content.= "namespace Models;\r\n\r\n";
    $model_code_content.= "\$GLOBALS['/Models/$model_name.php'] = 1;\r\n\r\n";
    $model_code_content.= "use Models\Base\Model;\r\n\r\n";
    $model_code_content.= "class $model_name extends Model{\r\n\r\n";
    foreach ($columns as $column) {
        $model_code_content.= "\t" . 'public static $field_' . $column['Field'] . ";\r\n";
    }
    $model_code_content.= "\tpublic static \$MODEL_SCHEMA;\r\n";

    $model_code_content.= "\r\n\tstatic function init_schema() {\r\n";
    $field_names = array();
    foreach ($columns as $column) {
        $field_name = $column['Field'];
        $field_names[] = "\t\t\tself::\$field_$field_name";
        $ai = stripos($column['Extra'], 'auto_increment') !== false;
        preg_match("/^(?'type'[a-zA-Z0-9_]+)/", $column['Type'], $matches);
        $db_type = $matches['type'];
        $type = 'string';
        $default_value = $column['Default'];
        if (strcasecmp($db_type, 'int') === 0 || strcasecmp($db_type, 'smallint') === 0 || strcasecmp($db_type, 'tinyint') === 0) {
            $type = 'int';
            $default_value = "0";
        } elseif (strcasecmp($db_type, 'decimal') === 0 || strcasecmp($db_type, 'float') === 0 || strcasecmp($db_type, 'double') === 0) {
            $type = 'float';
            $default_value = "0.00";
        } else if (strcasecmp($db_type, 'date') === 0 || strcasecmp($db_type, 'datetime') === 0) $type = $db_type;
        if (!isset($default_value) || strlen($default_value) === 0) $default_value = 'NULL';
        elseif (strcasecmp($type, 'string') === 0 && !in_array($default_value, array('0', '0.00'), true)) $default_value = "'" . $default_value . "'";
        if (stripos($column['Key'], "PRI") === 0) $model_code_content.= "\t\tself::\$field_$field_name = Model::define_primary_key('$field_name', '$type', $default_value, " . get_bool_string($ai) . ");\r\n";
        else $model_code_content.= "\t\tself::\$field_$field_name = Model::define_field('$field_name', '$type', $default_value);\r\n";
    }

    $model_code_content.= "\t\tself::\$MODEL_SCHEMA = Model::build_schema('$table_name', array(\r\n";
    $model_code_content.= implode(",\r\n", $field_names);
    $model_code_content.= "\r\n\t\t));\r\n";
    $model_code_content.= "\t}\r\n\r\n";

    foreach ($columns as $column) {
        $field_name = $column['Field'];
        $model_code_content.= "\r\n\tpublic function get_$field_name() {\r\n";
        $model_code_content.= "\t\treturn \$this->get_field_value(self::\$field_$field_name);\r\n";
        $model_code_content.= "\t}\r\n";

        $model_code_content.= "\r\n\tpublic function set_$field_name(\$$field_name) {\r\n";
        $model_code_content.= "\t\t\$this->set_field_value(self::\$field_$field_name, \$$field_name);\r\n";
        $model_code_content.= "\t}\r\n";
    };

    $model_code_content.= "\r\n\tpublic function to_array(array \$options = array(), callable \$func = NULL) {\r\n";
    $model_code_content.= "\t\t\$arr = parent::to_array(\$options, \$func);\r\n";
    foreach ($columns as $column) {
        $field_name = $column['Field'];
        $model_code_content.= "\t\t//unset(\$arr[self::\$field_{$field_name}['name']]);\r\n";
    };
    $model_code_content.= "\t\treturn \$arr;\r\n";
    $model_code_content.= "\t}\r\n";

    $model_code_content.= "\r\n}\r\n\r\n";
    $model_code_content.= "$model_name::init_schema();";
    $model_code_content.= "\r\n";

    $file_path = $document_root . '/Models/' . $model_name . '.php';
    $length = file_put_contents($file_path, $model_code_content);

    $status = $length === false ? 'failed' : 'successed';
    echo 'Build "' . $file_path . '" ' . $status . '<br>';
}