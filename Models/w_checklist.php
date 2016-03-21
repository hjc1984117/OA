<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/w_checklist.php'] = 1;

use Models\Base\Model;

class w_checklist extends Model{

	public static $field_atid;
	public static $field_checktime;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_atid = Model::define_field('atid', 'int', 0);
		self::$field_checktime = Model::define_field('checktime', 'datetime', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('w_checklist', array(
			self::$field_atid,
			self::$field_checktime
		));
	}


	public function get_atid() {
		return $this->get_field_value(self::$field_atid);
	}

	public function set_atid($atid) {
		$this->set_field_value(self::$field_atid, $atid);
	}

	public function get_checktime() {
		return $this->get_field_value(self::$field_checktime);
	}

	public function set_checktime($checktime) {
		$this->set_field_value(self::$field_checktime, $checktime);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_atid['name']]);
		//unset($arr[self::$field_checktime['name']]);
		return $arr;
	}

}

w_checklist::init_schema();
