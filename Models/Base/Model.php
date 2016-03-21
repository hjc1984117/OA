<?php

/**
 * 面向对象的数据操作基类
 *
 * @author ChenHao
 * @version 2015/1/10
 */

namespace Models\Base;

$GLOBALS['/Models/Base/Model.php'] = 1;

class Model {

    protected static function define_primary_key($name, $type, $default, $auto_increment = true) {
        return array('name' => $name, 'type' => $type, 'default' => $default, 'ai' => $auto_increment, 'pk' => true);
    }

    protected static function define_field($name, $type, $default = NULL) {
        return array('name' => $name, 'type' => $type, 'default' => $default, 'ai' => false, 'pk' => false);
    }

    protected static function build_schema($table_name, array $fields) {
        $schema = array('table_name' => $table_name, 'fields' => array(), 'operation_fields' => array());
        $pk_field = NULL;
        foreach ($fields as $field) {
            if ($field['pk']) {
                $schema['__PK__'] = $field;
                $pk_field = $field;
            }
            if (!$field['ai']) $schema['operation_fields'][$field['name']] = $field;
            $schema['fields'][$field['name']] = $field;
        }
        $fields = array_keys($schema['operation_fields']);
        $operation_fields_str = '`' . implode('`, `', $fields) . '`';
        $insert_values_str = implode(', :', $fields);
        $schema['insert_sql'] = "INSERT INTO `$table_name` ($operation_fields_str) VALUES (:$insert_values_str)";
        $schema['get_by_id_sql'] = "SELECT * FROM `$table_name` WHERE `$pk_field[name]` = :$pk_field[name]";
        return $schema;
    }

    private static function create_statement_error($message, \PDOStatement $stat) {
        $stat_error_array = array(
            false,
            'msg' => $message,
            'code' => $stat->errorCode(),
            'detail' => $stat->errorInfo()
        );
        if (strpos($stat_error_array['detail'][2], 'Duplicate entry') !== false) {
            $duplicate_entry = cut_str($stat_error_array['detail'][2], "entry '", "' for");
            if (strpos($stat_error_array['detail'][2], 'idx_uni_employee_no') !== false) {
                $stat_error_array['detail_cn'] = '已存在员工编号"' . $duplicate_entry . '"';
            }
            if (strpos($stat_error_array['detail'][2], 'idx_uni_idcard') !== false) {
                $stat_error_array['detail_cn'] = '已存在身份证号"' . $duplicate_entry . '"';
            }
        }
        return $stat_error_array;
    }

    public static function create_pdo_error($message, \PDO $db) {
        return array(
            false,
            'msg' => $message,
            'code' => $db->errorCode(),
            'detail' => $db->errorInfo()
        );
    }

    private static function create_db_error($message) {
        return array(
            false,
            'msg' => $message,
            'code' => PDO_ERROR_CODE,
            'detail' => $message);
    }

    /**
     * 根据主键查询实体
     * @param PDO $db PDO连接对象
     * @param Model $model 实体
     * @return Array
     */
    public static function get_by_primary_key(\PDO $db, Model $model) {
        $stat = $db->prepare($model->schema['get_by_id_sql']);
        if (!$stat) return self::create_pdo_error("准备根据ID查询实体{$model->class}时失败", $db);
        if (!self::bind_param($model->pk_field, $stat, $model)) return self::create_statement_error("根据ID查询实体{$model->class}时,绑定参数{$model->pk_field[name]}失败", $stat);
        if (!$stat->execute()) return self::create_statement_error("根据ID查询实体{$model->class}时,执行失败", $stat);
        $row = $stat->fetch(\PDO::FETCH_ASSOC);
        if (!$row) return array(true, 'loaded' => false);
        foreach ($row as $key => $value) {
            $model->set_field_value($key, $value);
        }
        return array(true, 'loaded' => true);
    }

    /**
     * 根据sql查询实体
     * @param PDO $db PDO连接对象
     * @param Model $template 实体模板
     * @param Model $model 需要回传的实体
     * @param string $sql sql语句
     * @return Array
     */
    public static function load_model(\PDO $db, Model $template, &$model, $sql = NULL) {
        if (!isset($sql) && empty($template->where_condition_fields)) {
            $template->set_where_and($template->pk_field, SqlOperator::Equals, $template->get_id());
        }
        $list = self::query_list($db, $template, $sql, false, 1);
        if (!$list[0]) return $list;
        $exists_model = !empty($list['models']);
        if ($exists_model) $model = $list['models'][0];
        return array($exists_model, $model);
    }

    /**
     * 根据sql查询实体列表
     * @param PDO $db PDO连接对象
     * @param Model $template 实体模板
     * @param string $sql sql语句
     * @param bool $fetch_total_count 是否需要查询符合条件的总数据行数(默认false不查询)
     * @param int $fetch_size 指定检索的数据行数
     * @return Array
     */
    public static function query_list(\PDO $db, Model $template, $sql = NULL, $fetch_total_count = false, $fetch_size = 0) {
        if (!isset($sql)) $sql = $template->sql_builder->to_sql_template();
        //SELECT * FROM M_User WHERE 1  AND `status` = :p_0 ORDER BY CONVERT(`employee_no`USING gbk) COLLATE gbk_chinese_ci ASC LIMIT 20, 20
        $stat = $db->prepare($sql);
        if (!$stat) return self::create_pdo_error("查询实体列表{$template->class}时失败", $db);
        foreach ($template->where_condition_fields as $field) {
            if (!self::bind_param($field, $stat, $template)) return self::create_statement_error("查询实体列表{$template->class}时,绑定参数$field[name]失败", $stat);
        }
        if (!$stat->execute()) return self::create_statement_error("查询实体列表{$template->class}时,执行失败", $stat);
        $class_name = get_class($template);
        $list = array();
        $fetch_num = 0;
        while (($row = $stat->fetch(\PDO::FETCH_ASSOC)) !== false) {
            $model = new $class_name($template->get_id());
            $model->query_fields = $template->query_fields;
            foreach ($row as $key => $value) {
                $model->set_field_value($key, $value, false);
            }
            $list[] = $model;
            if ($fetch_size > 0 && $fetch_num++ >= $fetch_size) break;
        }
        unset($stat);
        $result = array(true, 'count' => count($list), 'models' => $list);
        if ($fetch_total_count) $result['total_count'] = Model::model_count($db, $template);
        return $result;
    }

    private static function internal_save_model(\PDO $db, Model $model, $sql, $operation_fields, $ignore_affected_row_count = false) {
        $stat = $db->prepare($sql);
        if (!$stat) return self::create_pdo_error("保存实体{$model->class}时失败", $db);
        foreach ($operation_fields as $field) {
            if (!isset($model->key_values[$field['name']])) {
                $model->key_values[$field['name']] = $model->get_field_default_value($field);
            }
            if (!self::bind_param($field, $stat, $model)) return self::create_statement_error("保存实体{$model->class}时,绑定参数$field[name]失败", $stat);
        }
        if (!$stat->execute()) return self::create_statement_error("保存实体{$model->class}时,执行失败", $stat);
        $affected_row_count = $stat->rowCount();
        unset($stat);
        if (!$ignore_affected_row_count && $affected_row_count <= 0) return self::create_db_error("未能保存实体{$model->class}");
        $insert_id = (int) $db->lastInsertId();
        if ($model->pk_field['ai'] && $insert_id > 0) $model->set_id($insert_id);
        return array(true, $affected_row_count);
    }

    /**
     * 插入实体到数据库（建议调用实例方法insert）
     * @param PDO $db PDO连接对象
     * @param Model $model 实体
     * @return Array
     */
    public static function insert_model(\PDO $db, Model $model) {
        $sql = $model->schema['insert_sql'];
        return self::internal_save_model($db, $model, $sql, $model->schema['operation_fields']);
    }

    /**
     * 更新实体到数据库（建议调用实例方法update）
     * @param PDO $db PDO连接对象
     * @param Model $model 实体
     * @param bool $ignore_affected_row_count 是否忽略受影响的数据行数(默认false不忽略)
     * @return Array
     */
    public static function update_model(\PDO $db, Model $model, $ignore_affected_row_count = false) {
        $update_fields = $model->update_fields;
        if (count($update_fields) <= 0) return self::create_db_error('未设置任何需要更新的字段');
        $update_fields = array_map(function($field) {
            return '`' . $field['name'] . '` = :' . $field['name'];
        }, $update_fields);
        $update_fields_str = implode(', ', $update_fields);
        $where_condition_str = $model->sql_builder->get_where_clause();
        if (!isset($where_condition_str)) {
            $model->where_condition_fields[] = $model->pk_field;
            $pk_field_name = $model->pk_field['name'];
            $where_condition_str = " WHERE `$pk_field_name` = :$pk_field_name LIMIT 1";
        }
        $sql = "UPDATE `{$model->table_name}` SET {$update_fields_str}{$where_condition_str}";
        $operation_fields = array_merge($model->update_fields, $model->where_condition_fields);
        return self::internal_save_model($db, $model, $sql, $operation_fields, $ignore_affected_row_count);
    }

    /**
     * 从数据库删除实体（建议调用实例方法delete）
     * @param PDO $db PDO连接对象
     * @param Model $model 实体
     * @param bool $ignore_affected_row_count 是否忽略受影响的数据行数(默认false不忽略)
     * @return Array
     */
    public static function delete_model(\PDO $db, Model $model, $ignore_affected_row_count = false) {
        $where_condition_str = $model->sql_builder->get_where_clause();
        if (!isset($where_condition_str)) {
            $model->where_condition_fields[] = $model->pk_field;
            $pk_field_name = $model->pk_field['name'];
            $where_condition_str = " WHERE `$pk_field_name` = :$pk_field_name LIMIT 1";
        }
        $sql = "DELETE FROM `{$model->table_name}` $where_condition_str";
        $stat = $db->prepare($sql);
        if (!$stat) return self::create_pdo_error("删除实体{$model->class}时失败", $db);
        $operation_fields = $model->where_condition_fields;
        foreach ($operation_fields as $field) {
            if (!isset($model->key_values[$field['name']])) {
                $model->key_values[$field['name']] = $model->get_field_default_value($field);
            }
            if (!self::bind_param($field, $stat, $model)) return self::create_statement_error("删除实体{$model->class}时,绑定参数$field[name]失败", $stat);
        }
        if (!$stat->execute()) return self::create_statement_error("删除实体{$model->class}时，执行失败", $stat);
        $affected_row_count = $stat->rowCount();
        unset($stat);
        if (!$ignore_affected_row_count && $affected_row_count <= 0) return self::create_db_error("未能删除实体$model->class");
        return array(true, $affected_row_count);
    }

    /**
     * 判断数据库中是否存在指定的实体（建议调用实例方法exists）
     * @param PDO $db PDO连接对象
     * @param Model $model 实体
     * @return bool true存在，false不存在
     */
    public static function exists_model(\PDO $db, Model $model) {
        $sql = 'SELECT EXISTS (' . $model->sql_builder->to_sql_template() . ' LIMIT 1) AS is_exists';
        $result = Model::execute_scalar($db, $sql, $model);
        return $result['is_exists'] == 1;
    }

    /**
     * 聚合函数
     * @param \PDO $db
     * @param type $func_name
     * @param \Models\Base\Model $model
     * @param type $field
     * @return type
     */
    private static function aggregate_function(\PDO $db, Model $model, $field, $func_name, $alias = 'aggregate_result') {
        if (is_array($field)) $field_name = $field['name'];
        else $field_name = $field;
        $model1 = $model;
        $model1->set_query_fields($func_name . '(`' . $field_name . '`) AS ' . $alias);
        $model1->sql_builder->_order_by = array();
        $model1->set_limit_count(NULL);
        $result = Model::execute_scalar($db, NULL, $model1);
        return $result[$alias];
    }

    /**
     * 检索符合条件的实体数量（建议调用实例方法count）
     * @param PDO $db PDO连接对象
     * @param Model $model 实体
     * @return int 实体数量
     */
    public static function model_count(\PDO $db, Model $model) {
        $model1 = $model;
        $model1->set_query_fields('IFNULL(COUNT(*),0) AS total_record_count');
        $model1->sql_builder->_order_by = array();
        $model1->set_limit_count(NULL);
        $result = Model::execute_scalar($db, NULL, $model1);
        return $result['total_record_count'];
    }

    /**
     * 求和
     * @param \PDO $db
     * @param \Models\Base\Model $model
     * @param type $field
     * @return type
     */
    public static function model_sum(\PDO $db, Model $model, $field) {
        return self::aggregate_function($db, $model, $field, 'SUM', 'sum_result');
    }

    /**
     * 平均值
     * @param \PDO $db
     * @param \Models\Base\Model $model
     * @param type $field
     * @return type
     */
    public static function model_avg(\PDO $db, Model $model, $field) {
        return self::aggregate_function($db, $model, $field, 'AVG', 'avg_result');
    }

    /**
     * 最大值
     * @param \PDO $db
     * @param \Models\Base\Model $model
     * @param type $field
     * @return type
     */
    public static function model_max(\PDO $db, Model $model, $field) {
        return self::aggregate_function($db, $model, $field, 'MAX', 'max_result');
    }

    /**
     * 最小值
     * @param \PDO $db
     * @param \Models\Base\Model $model
     * @param type $field
     * @return type
     */
    public static function model_min(\PDO $db, Model $model, $field) {
        return self::aggregate_function($db, $model, $field, 'MIN', 'min_result');
    }

    public static function execute(\PDO $db, $sql = NULL, Model $template = NULL) {
        if (!isset($sql) && !isset($template)) die_error(RUNTIME_ERROR, 'sql语句和实体模板不能同时为空');
        if (!isset($sql)) $sql = $template->sql_builder->to_sql_template();
        $stat = $db->prepare($sql);
        $template_class_name = isset($template) ? "{$template->class}对象的" : '';
        if (!$stat) return self::create_pdo_error("执行{$template_class_name}sql: $sql 时失败", $db);
        if (isset($template)) {
            foreach ($template->where_condition_fields as $field) {
                if (!self::bind_param($field, $stat, $template)) return self::create_statement_error("查询实体{$template->class}时,绑定参数$field[name]失败", $stat);
            }
        }
        if (!$stat->execute()) return self::create_statement_error("执行{$template_class_name}sql: $sql 时失败，执行失败", $stat);
        $list = array();
        while (($row = $stat->fetch(\PDO::FETCH_ASSOC)) !== false) {
            $list[] = $row;
        }
        $count = $stat->rowCount();
        return array(true, 'count' => $count, 'results' => $list);
    }

    public static function execute_scalar(\PDO $db, $sql, Model $template) {
        if (!isset($sql)) $sql = $template->sql_builder->to_sql_template();
        $execute_result = Model::execute($db, $sql, $template);
        if (!$execute_result[0]) return $execute_result;
        $count = $execute_result['count'];
        $result = array(true, 'count' => $count);
        if ($count > 0 && count($execute_result['results']) > 0) $result = array_merge($result, $execute_result['results'][0]);
        return $result;
    }

    public static function execute_custom_sql(\PDO $db, $sql) {
        return Model::execute($db, $sql);
    }

    public static function insert_object($obj, $table_name, $db) {
        if (is_object($obj)) $obj = (array) $obj;
        $fields = array_keys($obj);
        array_walk($fields, function(&$field) {
            $field = "`$field`";
        });
        $fields_str = implode(', ', $fields);
        $values = array_values($obj);
        array_walk($values, function(&$value) {
            if (!is_numeric($val)) $value = "'$value'";
        });
        $values_str = implode(', ', $values);
        $sql = "INSERT INTO $table_name ($fields_str) VALUES ($values_str)";
        return Model::execute($db, $sql);
    }

    public static function array_to_dictionary(array $models, $field) {
        $dict = array();
        foreach ($models as $model) {
            $dict[$model->get_field_value($field)] = $model;
        }
        return $dict;
    }

    public static function list_to_array(array $models, array $options = array(), callable $func = NULL) {
        $arr = array();
        foreach ($models as $model) {
            $arr[] = $model->to_array($options, $func);
        }
        return $arr;
    }

    private static function bind_param(array $field, \PDOStatement $stat, Model $model) {
        $param_name = $field['name'];
        $type = $field['type'];
        if (!$model->exists_in_key_values($param_name)) return false;
        $value = $model->get_field_value($field);
        if (!isset($value)) $value = NULL;
        $param_type = \PDO::PARAM_STR;
        if (!isset($value)) {
            $param_type = \PDO::PARAM_NULL;
        } elseif (strcasecmp($type, 'int') === 0) {
            $param_type = \PDO::PARAM_INT;
        } elseif (strcasecmp($type, 'bool') === 0) {
            $param_type = \PDO::PARAM_BOOL;
        } elseif (strcasecmp($type, 'date') === 0) {
            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d');
            }
        } elseif (strcasecmp($type, 'datetime') === 0) {
            if ($value instanceof \DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }
        }
        if (strcmp($param_name{0}, ':') !== 0) {
            $param_name = ':' . $param_name;
        }
        return $stat->bindParam($param_name, $value, $param_type);
    }

    private $class;
    protected $key_values;
    protected $query_fields;
    protected $update_fields;
    protected $where_condition_fields;
    public $table_name;
    private $schema;
    private $pk_field;
    private $sql_builder;

    function __construct($id = 0, $init_default_values = false) {
        $class = get_class($this);
        $this->class = $class;
        $this->key_values = array();
        $this->query_fields = array();
        $this->update_fields = array();
        $this->where_condition_fields = array();
        $this->schema = $class::$MODEL_SCHEMA;
        $this->table_name = $this->schema['table_name'];
        $this->pk_field = $this->schema['__PK__'];
        if ($init_default_values) {
            foreach ($this->schema['fields'] as $name => $field) {
                $this->key_values[$name] = $this->get_field_default_value($field);
            }
        }
        $this->set_id($id);
        $this->sql_builder = new SqlBuilder($this);
    }

    /**
     * 对象重置
     * @param bool $init_default_values 是否初始化默认值
     * @return void
     */
    public function reset($init_default_values = false) {
        $pk_id = $this->get_field_value($this->pk_field);
        $this->key_values = array();
        $this->query_fields = array();
        $this->update_fields = array();
        $this->where_condition_fields = array();
        if ($init_default_values) {
            foreach ($this->schema['fields'] as $name => $field) {
                $this->key_values[$name] = $this->get_field_default_value($field);
            }
        }
        $this->set_id($pk_id);
        $this->sql_builder = new SqlBuilder($this);
    }

    public function get_pk_field() {
        return $this->pk_field;
    }

    public function get_field_by_name($field_name) {
        foreach ($this->schema['fields'] as $field) {
            if (str_equals($field['name'], $field_name)) return $field;
        }
    }

    public function get_field_name($field) {
        return $field['name'];
    }

    private function get_field_default_value(array $field) {
        $value = $field['default'];
        if (!isset($value)) {
            $type = $field['type'];
            if (strcasecmp('int', $type) === 0) return 0;
            if (strcasecmp('float', $type) === 0) return 0.0;
            //if (strcasecmp('date', $type) === 0 || strcasecmp('datetime', $type) === 0) return date_create();
            if (strcasecmp('bool', $type) === 0) return false;
            return NULL;
        }

        return $value;
    }

    public function get_field_value($field) {
        if (is_array($field)) {
            return $this->key_values[$field['name']];
        }
        return $this->key_values[$field];
    }

    public function set_field_from_array($array) {
        foreach ($array as $key => $value) {
            if (isset($value)) {
                if (strpos($value, "**") === false) {
                    $field = $this->get_field_by_name($key);
                    if (isset($field) && isset($value)) $this->set_field_value($field, $value);
                }
            }
        }
    }

    public function set_field_value($field, $value, $set_operation_fields = true, $set_where_condition_fields = false, $val_value = true) {
        if (!is_array($field)) {
            $field = $this->schema['fields'][$field];
        }
        $field_name = $field['name'];
        if ($val_value) {
            $value = $this->internal_val_value($field, $value);
        }
        $this->key_values[$field_name] = $value;

        if ($set_operation_fields && !$field['pk']) {
            $this->update_fields[] = $field;
        }
        if ($set_where_condition_fields) {
            $this->where_condition_fields[] = $field;
        }
    }

    public function internal_val_value($field, $value) {
        $field_name = $field['name'];
        $type = $field['type'];
        if (strcasecmp('int', $type) === 0) {
            $value = intval($value);
        } elseif (strcasecmp('float', $type) === 0) {
            $value = floatval($value);
        } elseif (strcasecmp('bool', $type) === 0) {
            $value = (bool) $value;
        } elseif (strcasecmp('date', $type) === 0 || strcasecmp('datetime', $type) === 0) {
            if (strlen($value) <= 0) unset($value);
            if (isset($value) && !($value instanceof \DateTime)) {
                $result = parse_date($value);
                if (!$result[0]) die_error(RUNTIME_ERROR, "给对象 $this->class 日期字段 $field_name 赋值时失败，$result[msg]");
                $value = $result['date'];
            }
        }
        return $value;
    }

    public function begin_where_group_condition() {
        $this->sql_builder->begin_where_group_condition();
    }

    public function end_where_group_condition() {
        $this->sql_builder->end_where_group_condition();
    }

    public function begin_having_group_condition() {
        $this->sql_builder->begin_having_group_condition();
    }

    public function end_having_group_condition() {
        $this->sql_builder->end_having_group_condition();
    }

    public function set_query_fields($fields) {
        $this->sql_builder->query_fields($fields);
    }

    public function set_custom_where($custom_condition) {
        $this->sql_builder->custom_where($custom_condition);
    }

    public function set_where_match_today($datetime_field) {
        $this->sql_builder->where_match_today($datetime_field);
    }

    public function set_where_match_this_week($datetime_field) {
        $this->sql_builder->where_match_this_week($datetime_field);
    }

    public function set_where_match_last_week($datetime_field) {
        $this->sql_builder->where_match_last_week($datetime_field);
    }

    public function set_where_match_this_month($datetime_field) {
        $this->sql_builder->where_match_this_month($datetime_field);
    }

    public function set_where_match_last_month($datetime_field) {
        $this->sql_builder->where_match_last_month($datetime_field);
    }

    public function set_where_match_some_month_ago($datetime_field, $num) {
        $this->sql_builder->where_match_some_month_ago($datetime_field, $num);
    }

    public function set_where_from_date_to_now($datetime_field, $num) {
        $this->sql_builder->where_from_date_to_now($datetime_field, $num);
    }

    public function set_where($field, $sql_operator, $value = '', $sql_logic = 'AND') {
        $this->sql_builder->where($field, $sql_operator, $value, $sql_logic);
    }

    public function set_where_and($field, $sql_operator, $value = '') {
        $this->sql_builder->where_and($field, $sql_operator, $value);
    }

    public function set_where_or($field, $sql_operator, $value = '') {
        $this->sql_builder->where_or($field, $sql_operator, $value);
    }

    public function set_order_by($field, $sql_sort_type = SqlSortType::__default) {
        $this->sql_builder->order_by($field, $sql_sort_type);
    }

    public function set_custom_order_by($custom_order_condition) {
        $this->sql_builder->custom_order_by($custom_order_condition);
    }

    public function set_group_by($field) {
        $this->sql_builder->group_by($field);
    }

    public function set_limit_count($limit) {
        $this->sql_builder->limit_count($limit);
    }

    public function set_limit($start_index, $fetch_size) {
        $this->sql_builder->limit($start_index, $fetch_size);
    }

    public function set_limit_paged($page_index, $fetch_size) {
        $this->sql_builder->limit_paged($page_index, $fetch_size);
    }

    public function get_id() {
        return $this->get_field_value($this->pk_field);
    }

    public function set_id($id) {
        $this->set_field_value($this->pk_field, $id);
    }

    public function load(\PDO $db, Model &$model, $sql = NULL) {
        return self::load_model($db, $this, $model, $sql);
    }

    public function insert(\PDO $db) {
        return self::insert_model($db, $this);
    }

    public function update(\PDO $db, $ignore_affected_row_count = false) {
        return self::update_model($db, $this, $ignore_affected_row_count);
    }

    public function delete(\PDO $db, $ignore_affected_row_count = false) {
        return self::delete_model($db, $this, $ignore_affected_row_count);
    }

    public function exists(\PDO $db) {
        return self::exists_model($db, $this);
    }

    public function count(\PDO $db) {
        return self::model_count($db, $this);
    }

    public function sum(\PDO $db, array $field) {
        return self::model_sum($db, $this, $field);
    }

    public function avg(\PDO $db, array $field) {
        return self::model_avg($db, $this, $field);
    }

    public function max(\PDO $db, array $field) {
        return self::model_max($db, $this, $field);
    }

    public function min(\PDO $db, array $field) {
        return self::model_min($db, $this, $field);
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __isset($name) {
        return array_key_exists($name, $this->key_values) && isset($this->key_values[$name]);
    }

    public function exists_in_key_values($name) {
        return array_key_exists($name, $this->key_values);
    }

    /**
     * 对象转数组
     * @param array $options 输出选项ModelToArrayOptions，e.g. array(ModelToArrayOptions::All2Str)
     * @param callable $func 输出时对数组元素进行处理的回调函数
     * @return array 对象转后后的数组
     */
    public function to_array(array $options = array(), callable $func = NULL) {
        $arr = array();
        foreach ($this->schema['fields'] as $field) {
            $value = $this->get_field_value($field);
            if (!isset($value) && in_array(ModelToArrayOptions::DefaultValue, $options)) {
                $value = $this->get_field_default_value($field);
            }
            $type = $field['type'];
            if (strcasecmp('date', $type) === 0 && ($value instanceof \DateTime)) {
                $value = $value->format("Y-m-d");
            } else if (strcasecmp('datetime', $type) === 0 && ($value instanceof \DateTime)) {
                $value = $value->format("Y-m-d H:i:s");
            }
            if (in_array($type, array('date', 'datetime')) && in_array(ModelToArrayOptions::Str2Time, $options)) {
                $value = strtotime($value);
            }
            if (in_array(ModelToArrayOptions::All2Str, $options)) {
                $value = (string) $value;
            }
            $arr[$field['name']] = $value;
        }
        if (!empty($this->query_fields) && !in_array('*', $this->query_fields)) {
            $query_fields = $this->query_fields;
            $query_arr = array();
            array_walk($arr, function($val, $key) use($query_fields, &$query_arr) {
                if (in_array($key, $query_fields)) $query_arr[$key] = $val;
            });
            $arr = $query_arr;
        }
        if (isset($func)) {
            $func($arr);
        }
        if (in_array(ModelToArrayOptions::ValueOnly, $options)) {
            $arr = array_values($arr);
        }
        return $arr;
    }

}
