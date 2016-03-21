<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/w_mealstable.php'] = 1;

use Models\Base\Model;

class w_mealstable extends Model{

	public static $field_id;
	public static $field_userid;
	public static $field_dept_id;
	public static $field_add_date;
	public static $field_ma_out;
	public static $field_ma_in;
	public static $field_mn_out;
	public static $field_mn_in;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, false);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$field_dept_id = Model::define_field('dept_id', 'int', 0);
		self::$field_add_date = Model::define_field('add_date', 'date', NULL);
		self::$field_ma_out = Model::define_field('ma_out', 'datetime', NULL);
		self::$field_ma_in = Model::define_field('ma_in', 'datetime', NULL);
		self::$field_mn_out = Model::define_field('mn_out', 'datetime', NULL);
		self::$field_mn_in = Model::define_field('mn_in', 'datetime', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('w_mealstable', array(
			self::$field_id,
			self::$field_userid,
			self::$field_dept_id,
			self::$field_add_date,
			self::$field_ma_out,
			self::$field_ma_in,
			self::$field_mn_out,
			self::$field_mn_in
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_userid() {
		return $this->get_field_value(self::$field_userid);
	}

	public function set_userid($userid) {
		$this->set_field_value(self::$field_userid, $userid);
	}

	public function get_dept_id() {
		return $this->get_field_value(self::$field_dept_id);
	}

	public function set_dept_id($dept_id) {
		$this->set_field_value(self::$field_dept_id, $dept_id);
	}

	public function get_add_date() {
		return $this->get_field_value(self::$field_add_date);
	}

	public function set_add_date($add_date) {
		$this->set_field_value(self::$field_add_date, $add_date);
	}

	public function get_ma_out() {
		return $this->get_field_value(self::$field_ma_out);
	}

	public function set_ma_out($ma_out) {
		$this->set_field_value(self::$field_ma_out, $ma_out);
	}

	public function get_ma_in() {
		return $this->get_field_value(self::$field_ma_in);
	}

	public function set_ma_in($ma_in) {
		$this->set_field_value(self::$field_ma_in, $ma_in);
	}

	public function get_mn_out() {
		return $this->get_field_value(self::$field_mn_out);
	}

	public function set_mn_out($mn_out) {
		$this->set_field_value(self::$field_mn_out, $mn_out);
	}

	public function get_mn_in() {
		return $this->get_field_value(self::$field_mn_in);
	}

	public function set_mn_in($mn_in) {
		$this->set_field_value(self::$field_mn_in, $mn_in);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_dept_id['name']]);
		//unset($arr[self::$field_add_date['name']]);
		//unset($arr[self::$field_ma_out['name']]);
		//unset($arr[self::$field_ma_in['name']]);
		//unset($arr[self::$field_mn_out['name']]);
		//unset($arr[self::$field_mn_in['name']]);
		return $arr;
	}

}

w_mealstable::init_schema();
