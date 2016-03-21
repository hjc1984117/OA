<?php

/**
* 调休
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/A_AdjustRest.php'] = 1;

use Models\Base\Model;

class A_AdjustRest extends Model{

	public static $field_id;
	public static $field_add_time;
	public static $field_userid;
	public static $field_username;
	public static $field_dept1_id;
	public static $field_phone;
	public static $field_rest_date;
	public static $field_rest_days;
	public static $field_adjust_to;
	public static $field_adjust_days;
	public static $field_status;
	public static $field_reason;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_add_time = Model::define_field('add_time', 'date', NULL);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$field_username = Model::define_field('username', 'string', NULL);
		self::$field_dept1_id = Model::define_field('dept1_id', 'int', 0);
		self::$field_phone = Model::define_field('phone', 'string', NULL);
		self::$field_rest_date = Model::define_field('rest_date', 'datetime', NULL);
		self::$field_rest_days = Model::define_field('rest_days', 'float', 0.00);
		self::$field_adjust_to = Model::define_field('adjust_to', 'datetime', NULL);
		self::$field_adjust_days = Model::define_field('adjust_days', 'float', 0.00);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$field_reason = Model::define_field('reason', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('A_AdjustRest', array(
			self::$field_id,
			self::$field_add_time,
			self::$field_userid,
			self::$field_username,
			self::$field_dept1_id,
			self::$field_phone,
			self::$field_rest_date,
			self::$field_rest_days,
			self::$field_adjust_to,
			self::$field_adjust_days,
			self::$field_status,
			self::$field_reason
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_add_time() {
		return $this->get_field_value(self::$field_add_time);
	}

	public function set_add_time($add_time) {
		$this->set_field_value(self::$field_add_time, $add_time);
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

	public function get_phone() {
		return $this->get_field_value(self::$field_phone);
	}

	public function set_phone($phone) {
		$this->set_field_value(self::$field_phone, $phone);
	}

	public function get_rest_date() {
		return $this->get_field_value(self::$field_rest_date);
	}

	public function set_rest_date($rest_date) {
		$this->set_field_value(self::$field_rest_date, $rest_date);
	}

	public function get_rest_days() {
		return $this->get_field_value(self::$field_rest_days);
	}

	public function set_rest_days($rest_days) {
		$this->set_field_value(self::$field_rest_days, $rest_days);
	}

	public function get_adjust_to() {
		return $this->get_field_value(self::$field_adjust_to);
	}

	public function set_adjust_to($adjust_to) {
		$this->set_field_value(self::$field_adjust_to, $adjust_to);
	}

	public function get_adjust_days() {
		return $this->get_field_value(self::$field_adjust_days);
	}

	public function set_adjust_days($adjust_days) {
		$this->set_field_value(self::$field_adjust_days, $adjust_days);
	}

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function get_reason() {
		return $this->get_field_value(self::$field_reason);
	}

	public function set_reason($reason) {
		$this->set_field_value(self::$field_reason, $reason);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_add_time['name']]);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_username['name']]);
		//unset($arr[self::$field_dept1_id['name']]);
		//unset($arr[self::$field_phone['name']]);
		//unset($arr[self::$field_rest_date['name']]);
		//unset($arr[self::$field_rest_days['name']]);
		//unset($arr[self::$field_adjust_to['name']]);
		//unset($arr[self::$field_adjust_days['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_reason['name']]);
		return $arr;
	}

}

A_AdjustRest::init_schema();
