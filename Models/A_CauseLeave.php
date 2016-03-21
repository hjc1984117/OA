<?php

/**
* 请假
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/A_CauseLeave.php'] = 1;

use Models\Base\Model;

class A_CauseLeave extends Model{

	public static $field_id;
	public static $field_userid;
	public static $field_username;
	public static $field_dept1_id;
	public static $field_dept2_id;
	public static $field_reason;
	public static $field_starttime;
	public static $field_endtime;
	public static $field_hours;
	public static $field_auditor;
	public static $field_status;
	public static $field_type;
	public static $field_salary;
	public static $field_date;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$field_username = Model::define_field('username', 'string', NULL);
		self::$field_dept1_id = Model::define_field('dept1_id', 'int', 0);
		self::$field_dept2_id = Model::define_field('dept2_id', 'int', 0);
		self::$field_reason = Model::define_field('reason', 'string', NULL);
		self::$field_starttime = Model::define_field('starttime', 'datetime', NULL);
		self::$field_endtime = Model::define_field('endtime', 'datetime', NULL);
		self::$field_hours = Model::define_field('hours', 'int', 0);
		self::$field_auditor = Model::define_field('auditor', 'int', 0);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$field_type = Model::define_field('type', 'int', 0);
		self::$field_salary = Model::define_field('salary', 'float', 0.00);
		self::$field_date = Model::define_field('date', 'date', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('A_CauseLeave', array(
			self::$field_id,
			self::$field_userid,
			self::$field_username,
			self::$field_dept1_id,
			self::$field_dept2_id,
			self::$field_reason,
			self::$field_starttime,
			self::$field_endtime,
			self::$field_hours,
			self::$field_auditor,
			self::$field_status,
			self::$field_type,
			self::$field_salary,
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

	public function get_reason() {
		return $this->get_field_value(self::$field_reason);
	}

	public function set_reason($reason) {
		$this->set_field_value(self::$field_reason, $reason);
	}

	public function get_starttime() {
		return $this->get_field_value(self::$field_starttime);
	}

	public function set_starttime($starttime) {
		$this->set_field_value(self::$field_starttime, $starttime);
	}

	public function get_endtime() {
		return $this->get_field_value(self::$field_endtime);
	}

	public function set_endtime($endtime) {
		$this->set_field_value(self::$field_endtime, $endtime);
	}

	public function get_hours() {
		return $this->get_field_value(self::$field_hours);
	}

	public function set_hours($hours) {
		$this->set_field_value(self::$field_hours, $hours);
	}

	public function get_auditor() {
		return $this->get_field_value(self::$field_auditor);
	}

	public function set_auditor($auditor) {
		$this->set_field_value(self::$field_auditor, $auditor);
	}

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function get_type() {
		return $this->get_field_value(self::$field_type);
	}

	public function set_type($type) {
		$this->set_field_value(self::$field_type, $type);
	}

	public function get_salary() {
		return $this->get_field_value(self::$field_salary);
	}

	public function set_salary($salary) {
		$this->set_field_value(self::$field_salary, $salary);
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
		//unset($arr[self::$field_reason['name']]);
		//unset($arr[self::$field_starttime['name']]);
		//unset($arr[self::$field_endtime['name']]);
		//unset($arr[self::$field_hours['name']]);
		//unset($arr[self::$field_auditor['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_type['name']]);
		//unset($arr[self::$field_salary['name']]);
		//unset($arr[self::$field_date['name']]);
		return $arr;
	}

}

A_CauseLeave::init_schema();
