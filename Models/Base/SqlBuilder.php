<?php

/**
 * SQL语句构造器
 *
 * @author ChenHao
 * @version 2015/1/10
 */

namespace Models\Base;

$GLOBALS['/Models/Base/SqlBuilder.php'] = 1;

class SqlBuilder {

    private $_model = NULL;
    private $_fields = '*';
    private $_where = NULL;
    private $_having = NULL;
    private $_where_template = NULL;
    private $_having_template = NULL;
    private $_group_by = array();
    private $_order_by = array();
    private $_limit = NULL;
    private $_param_index = 0;

    public function __construct(Model &$model) {
        $this->_model = $model;
        //$this->init();
        return $this;
    }

    public function __get($name) {
        return $this->$name;
    }

    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __isset($name) {
        return isset($this->$name);
    }

    public function init() {
        $this->_fields = '*';
        $this->_where = NULL;
        $this->_having = NULL;
        $this->_where_template = NULL;
        $this->_having_template = NULL;
        $this->_group_by = array();
        $this->_order_by = array();
        $this->_limit = NULL;
        $this->_param_index = 0;
    }

    public function query_fields($fields, $distinct = false) {
        if (is_array($fields)) {
            if (is_array($fields[0]) && array_key_exists('name', $fields[0])) {
                $fields_name = array();
                foreach ($fields as $field) {
                    $fields_name[] = '`' . $field['name'] . '`';
                }
                $this->_fields = implode(', ', $fields_name);
            } elseif (is_string($fields[0])) {
                $this->_fields = implode(', ', $fields);
            }
        } elseif (is_string($fields)) {
            $this->_fields = $fields;
        }
        if ($distinct) $this->_fields = 'DISTINCT ' . $this->_fields;
        $this->_model->query_fields = explode(',', str_replace('`', '', str_replace(' ', '', $this->_fields)));
        return $this;
    }

    private function begin_group_condition($where_or_having = 1) {
        if ($where_or_having == 1) {
            $this->_where.='(';
            $this->_where_template.='(';
        } else {
            $this->_having.='(';
            $this->_having_template.='(';
        }
        return $this;
    }

    private function end_group_condition($where_or_having = 1) {
        if ($where_or_having == 1) {
            $this->_where.=')';
            $this->_where_template.=')';
        } else {
            $this->_having.=')';
            $this->_having_template.=')';
        }
        return $this;
    }

    public function begin_where_group_condition() {
        return $this->begin_group_condition(1);
    }

    public function end_where_group_condition() {
        return $this->end_group_condition(1);
    }

    public function begin_having_group_condition() {
        return $this->begin_group_condition(2);
    }

    public function end_having_group_condition() {
        return $this->end_group_condition(2);
    }

    public function where_string($where) {
        $this->_where.= ' ' . $where;
        return $this;
    }

    public function where($field, $sql_operator, $value = '', $sql_logic = 'AND') {
        return $this->internal_where_having($field, $sql_operator, $value, $sql_logic, 1);
    }

    public function where_and($field, $sql_operator, $value = '') {
        return $this->where($field, $sql_operator, $value, 'AND');
    }

    public function where_or($field, $sql_operator, $value = '') {
        return $this->where($field, $sql_operator, $value, 'OR');
    }

    public function having_string($having) {
        $this->_having.= ' ' . $having;
        return $this;
    }

    public function having($field, $sql_operator, $value = '', $sql_logic = 'AND') {
        return $this->internal_where_having($field, $sql_operator, $value, $sql_logic, 2);
    }

    public function having_and($field, $sql_operator, $value = '') {
        return $this->having($field, $sql_operator, $value, 'AND');
    }

    public function having_or($field, $sql_operator, $value = '') {
        return $this->having($field, $sql_operator, $value, 'OR');
    }

    public function order_by($field, $sql_sort_type = SqlSortType::__default) {
        if (isset($field) && strlen($field['name']) > 0) {
            if (strcasecmp('string', $field['type']) === 0) {
                $this->_order_by[] = 'CONVERT(`' . $field['name'] . '` USING gbk) COLLATE gbk_chinese_ci ' . $sql_sort_type;
            } else {
                $this->_order_by[] = '`' . $field['name'] . '` ' . $sql_sort_type;
            }
        }
        return $this;
    }

    public function group_by($field) {
        if (isset($field) && strlen($field['name']) > 0) {
            $this->_group_by[] = '`' . $field['name'] . '`';
        }
        return $this;
    }

    public function limit_count($fetch_size) {
        $this->_limit = $fetch_size;
        return $this;
    }

    public function limit($start_index, $fetch_size) {
        $this->_limit = $start_index . ', ' . $fetch_size;
        return $this;
    }

    public function limit_paged($page_index, $fetch_size) {
        $start_index = $fetch_size * ($page_index - 1);
        return $this->limit($start_index, $fetch_size);
    }

    public function get_where_clause($to_sql_template = true) {
        $sql = NULL;
        $where = $to_sql_template ? $this->_where_template : $this->_where;
        if (isset($where)) {
            $sql.=' WHERE 1 ' . $where;
        }
        if (isset($this->_limit)) {
            $sql.=' LIMIT ' . $this->_limit;
        }
        return $sql;
    }

    public function to_sql() {
        return $this->internal_to_sql();
    }

    public function to_sql_template() {
        return $this->internal_to_sql(true);
    }

    private function internal_to_sql($to_sql_template = false) {
        /* @var $model Model */
        $sql = 'SELECT ' . $this->_fields . ' FROM ' . $this->_model->table_name;
        $where = $to_sql_template ? $this->_where_template : $this->_where;
        $having = $to_sql_template ? $this->_having_template : $this->_having;
        if (isset($where)) {
            $sql.=' WHERE 1 ' . $where;
        }
        if (!empty($this->_group_by)) {
            $sql.=' GROUP BY ' . implode(', ', $this->_group_by);
        }
        if (isset($having)) {
            $sql.=' HAVING 1 ' . $having;
        }
        if (!empty($this->_order_by)) {
            $sql.=' ORDER BY ' . implode(', ', $this->_order_by);
        }
        if (isset($this->_limit)) {
            $sql.=' LIMIT ' . $this->_limit;
        }
        $this->_model->sql_builder = $this;
        //$this->init();
        return $sql;
    }

    private function internal_where_having($field, $sql_operator, $value, $sql_logic = 'AND', $where_or_having = 1) {
        $field_name = $field['name'];
        $field_type = $field['type'];
        if (strcmp(strrchr($this->_where, '('), '(') === 0) {
            $sql_logic .= ' (';
            $this->_where = rtrim($this->_where, '(');
            $this->_where_template = rtrim($this->_where_template, '(');
        }
        if (strcmp(strrchr($this->_having, '('), '(') === 0) {
            $sql_logic .= ' (';
            $this->_having = rtrim($this->_having, '(');
            $this->_having_template = rtrim($this->_having_template, '(');
        }
        switch ($sql_operator) {
            case SqlOperator::IsNull:
            case SqlOperator::IsNotNull:
                $condition = ' ' . $sql_logic . ' `' . $field_name . '` ' . $sql_operator;
                $condition_template = $condition;
                break;
            case SqlOperator::IsNullOrEmpty:
            case SqlOperator::IsNotNullAndEmpty:
                $sql_operator = sprintf($sql_operator, '`' . $field_name . '`');
                $condition = ' ' . $sql_logic . ' (`' . $field_name . '` ' . $sql_operator . ')';
                $condition_template = $condition;
                break;
            case SqlOperator::In:
            case SqlOperator::NotIn:
                if (!is_array($value) || count($value) < 1) return $this;
                array_walk($value, function(&$val) use($field_type) {
                    $val = $this->internal_escape_string($field_type, $val);
                });
                $value_count = count($value);
                $in_params = array();
                for ($i = 0; $i < $value_count; $i++) {
                    $in_param_name = ':p_' . $this->_param_index . '_' . $i;
                    $in_params[] = $in_param_name;
                    $field['name'] = $in_param_name;
                    $this->_model->set_field_value($field, $value[$i], false, true, false);
                }
                $condition_template = ' ' . $sql_logic . ' `' . $field_name . '` ' . $sql_operator . ' (' . implode(', ', $in_params) . ')';
                array_walk($value, function(&$val) {
                    if (!is_numeric($val)) $val = "'$val'";
                });
                $condition = ' ' . $sql_logic . ' `' . $field_name . '` ' . $sql_operator . ' (' . implode(', ', $value) . ')';
                break;
            case SqlOperator::Between:
            case SqlOperator::NotBetween:
                if (!is_array($value) || count($value) != 2) return $this;
                $start_value = $this->internal_escape_string($field_type, $value[0]);
                $end_value = $this->internal_escape_string($field_type, $value[1]);
                $param_start_name = ':p_' . $this->_param_index . '_0';
                $param_end_name = ':p_' . $this->_param_index . '_1';
                $field['name'] = $param_start_name;
                $this->_model->set_field_value($field, $start_value, false, true, false);
                $field['name'] = $param_end_name;
                $this->_model->set_field_value($field, $end_value, false, true, false);
                $condition_template = ' ' . $sql_logic . ' `' . $field_name . '` ' . sprintf($sql_operator, $param_start_name, $param_end_name);
                if (!is_numeric($start_value)) $start_value = "'$start_value'";
                if (!is_numeric($end_value)) $end_value = "'$end_value'";
                $condition = ' ' . $sql_logic . ' `' . $field_name . '` ' . sprintf($sql_operator, $start_value, $end_value);
                break;
            default:
                $value = $this->internal_escape_string($field_type, $value);
                $field['name'] = ':p_' . $this->_param_index;
                $this->_model->set_field_value($field, $value, false, true, false);
                $condition_template = ' ' . $sql_logic . ' `' . $field_name . '` ' . $sql_operator . ' ' . $field['name'];
                if (!is_numeric($value)) $value = "'$value'";
                $condition = ' ' . $sql_logic . ' `' . $field_name . '` ' . $sql_operator . ' ' . $value;
        }
        if ($where_or_having == 1) {
            $this->_where.=$condition;
            $this->_where_template.=$condition_template;
        } else {
            $this->_having.=$condition;
            $this->_having_template.=$condition_template;
        }
        $this->_param_index++;
        return $this;
    }

//    public function custom_where($custom_field,$sql_operator, $value, $sql_logic = 'AND'){
//        
//    }

    public function custom_where($custom_condition) {
        $this->_where.=$custom_condition;
        $this->_where_template.=$custom_condition;
    }

    public function custom_order_by($custom_condition) {
        $this->_order_by[] = ' ' . $custom_condition . ' ';
    }

    private function get_field_name($field) {
        if (is_array($field)) {
            return $field['name'];
        } else {
            return $field;
        }
    }

    public function where_match_today($datetime_field) {
        $this->custom_where(' AND DATE(`' . $this->get_field_name($datetime_field) . '`) = CURDATE() ');
    }

    public function where_match_this_week($datetime_field) {
        $this->custom_where(' AND YEARWEEK(DATE_FORMAT(`' . $this->get_field_name($datetime_field) . '`,\'%Y-%m-%d\')) = YEARWEEK(NOW()) ');
    }

    public function where_match_last_week($datetime_field) {
        $this->custom_where(' AND YEARWEEK(DATE_FORMAT(`' . $this->get_field_name($datetime_field) . '`,\'%Y-%m-%d\')) = YEARWEEK(NOW())-1 ');
    }

    public function where_match_this_month($datetime_field) {
        $this->custom_where(' AND DATE_FORMAT(`' . $this->get_field_name($datetime_field) . '`,\'%Y-%m\') = DATE_FORMAT(NOW(),\'%Y-%m\') ');
    }

    public function where_match_last_month($datetime_field) {
        $this->custom_where(' AND DATE_FORMAT(`' . $this->get_field_name($datetime_field) . '`,\'%Y-%m\') = DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL 1 MONTH),\'%Y-%m\') ');
    }

    public function where_match_some_month_ago($datetime_field, $num) {
        $this->custom_where(' AND DATE_FORMAT(`' . $this->get_field_name($datetime_field) . '`,\'%Y-%m\') = DATE_FORMAT(DATE_SUB(CURDATE(), INTERVAL ' . $num . ' MONTH),\'%Y-%m\') ');
    }

    public function where_from_date_to_now($datetime_field, $num) {
        $this->custom_where(' AND `' . $this->get_field_name($datetime_field) . '` BETWEEN DATE_SUB(NOW(),INTERVAL ' . $num . ' MONTH) AND NOW() ');
    }

    private function internal_escape_string($type, $value) {
        //$type = $field['type'];
        //$field_name = $field['name'];
        if (strcasecmp('int', $type) === 0) {
            $value = intval($value);
        } elseif (strcasecmp('float', $type) === 0) {
            $value = floatval($value);
        } elseif (strcasecmp('bool', $type) === 0) {
            $value = (bool) $value;
        } elseif (strcasecmp('date', $type) === 0 || strcasecmp('datetime', $type) === 0) {
            if ($value instanceof \DateTime) {
                $value = strcasecmp('datetime', $type) === 0 ? $value->format('Y-m-d H:i:s') : $value->format('Y-m-d');
            }
        } else {
            $value = mysql_escape_string($value);
        }
        return $value;
    }

}
