<?php

/**
* KEY_VALUE保存键值对
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/S_KV.php'] = 1;

use Models\Base\Model;

class S_KV extends Model{

	public static $field_key;
	public static $field_value;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_key = Model::define_primary_key('key', 'string', NULL, false);
		self::$field_value = Model::define_field('value', 'string', 0);
		self::$MODEL_SCHEMA = Model::build_schema('S_KV', array(
			self::$field_key,
			self::$field_value
		));
	}


	public function get_key() {
		return $this->get_field_value(self::$field_key);
	}

	public function set_key($key) {
		$this->set_field_value(self::$field_key, $key);
	}

	public function get_value() {
		return $this->get_field_value(self::$field_value);
	}

	public function set_value($value) {
		$this->set_field_value(self::$field_value, $value);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_key['name']]);
		//unset($arr[self::$field_value['name']]);
		return $arr;
	}

}

S_KV::init_schema();
