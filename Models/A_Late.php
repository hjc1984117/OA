<?php

/**
* 迟到
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/A_Late.php'] = 1;

use Models\Base\Model;

class A_Late extends Model{

	public static $field_id;
	public static $field_userid;
	public static $field_username;
	public static $field_dept1_id;
	public static $field_dept2_id;
	public static $field_mins;
	public static $field_date;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$field_username = Model::define_field('username', 'string', NULL);
		self::$field_dept1_id = Model::define_field('dept1_id', 'int', 0);
		self::$field_dept2_id = Model::define_field('dept2_id', 'int', 0);
		self::$field_mins = Model::define_field('mins', 'float', 0.00);
		self::$field_date = Model::define_field('date', 'date', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('A_Late', array(
			self::$field_id,
			self::$field_userid,
			self::$field_username,
			self::$field_dept1_id,
			self::$field_dept2_id,
			self::$field_mins,
			self::$field_date
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

	public function get_username() {
		return $this->get_field_value(self::$field_username);
	}

	public function set_username($username) {
		$this->set_field_value(self::$field_username, $username);
	}

	public function get_dept1_id() {
		return $this->get_field_value(self::$field_dept1_id);
	}

	public function set_dept1_id($dept1_id) {
		$this->set_field_value(self::$field_dept1_id, $dept1_id);
	}

	public function get_dept2_id() {
		return $this->get_field_value(self::$field_dept2_id);
	}

	public function set_dept2_id($dept2_id) {
		$this->set_field_value(self::$field_dept2_id, $dept2_id);
	}

	public function get_mins() {
		return $this->get_field_value(self::$field_mins);
	}

	public function set_mins($mins) {
		$this->set_field_value(self::$field_mins, $mins);
	}

	public function get_date() {
		return $this->get_field_value(self::$field_date);
	}

	public function set_date($date) {
		$this->set_field_value(self::$field_date, $date);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_username['name']]);
		//unset($arr[self::$field_dept1_id['name']]);
		//unset($arr[self::$field_dept2_id['name']]);
		//unset($arr[self::$field_mins['name']]);
		//unset($arr[self::$field_date['name']]);
		return $arr;
	}

}

A_Late::init_schema();
