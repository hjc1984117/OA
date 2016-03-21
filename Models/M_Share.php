<?php

/**
* 员工股份
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/M_Share.php'] = 1;

use Models\Base\Model;

class M_Share extends Model{

	public static $field_userid;
	public static $field_employee_no;
	public static $field_username;
	public static $field_dept1_id;
	public static $field_dept2_id;
	public static $field_role_id;
	public static $field_join_time;
	public static $field_join_days;
	public static $field_work_age;
	public static $field_work_shares;
	public static $field_position_shares;
	public static $field_sanction_shares;
	public static $field_current_shares;
	public static $field_status;
	public static $field_remark;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_userid = Model::define_primary_key('userid', 'int', 0, false);
		self::$field_employee_no = Model::define_field('employee_no', 'string', NULL);
		self::$field_username = Model::define_field('username', 'string', NULL);
		self::$field_dept1_id = Model::define_field('dept1_id', 'string', NULL);
		self::$field_dept2_id = Model::define_field('dept2_id', 'string', NULL);
		self::$field_role_id = Model::define_field('role_id', 'string', NULL);
		self::$field_join_time = Model::define_field('join_time', 'datetime', NULL);
		self::$field_join_days = Model::define_field('join_days', 'int', 0);
		self::$field_work_age = Model::define_field('work_age', 'int', 0);
		self::$field_work_shares = Model::define_field('work_shares', 'string', NULL);
		self::$field_position_shares = Model::define_field('position_shares', 'string', NULL);
		self::$field_sanction_shares = Model::define_field('sanction_shares', 'string', NULL);
		self::$field_current_shares = Model::define_field('current_shares', 'string', NULL);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('M_Share', array(
			self::$field_userid,
			self::$field_employee_no,
			self::$field_username,
			self::$field_dept1_id,
			self::$field_dept2_id,
			self::$field_role_id,
			self::$field_join_time,
			self::$field_join_days,
			self::$field_work_age,
			self::$field_work_shares,
			self::$field_position_shares,
			self::$field_sanction_shares,
			self::$field_current_shares,
			self::$field_status,
			self::$field_remark
		));
	}


	public function get_userid() {
		return $this->get_field_value(self::$field_userid);
	}

	public function set_userid($userid) {
		$this->set_field_value(self::$field_userid, $userid);
	}

	public function get_employee_no() {
		return $this->get_field_value(self::$field_employee_no);
	}

	public function set_employee_no($employee_no) {
		$this->set_field_value(self::$field_employee_no, $employee_no);
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

	public function get_role_id() {
		return $this->get_field_value(self::$field_role_id);
	}

	public function set_role_id($role_id) {
		$this->set_field_value(self::$field_role_id, $role_id);
	}

	public function get_join_time() {
		return $this->get_field_value(self::$field_join_time);
	}

	public function set_join_time($join_time) {
		$this->set_field_value(self::$field_join_time, $join_time);
	}

	public function get_join_days() {
		return $this->get_field_value(self::$field_join_days);
	}

	public function set_join_days($join_days) {
		$this->set_field_value(self::$field_join_days, $join_days);
	}

	public function get_work_age() {
		return $this->get_field_value(self::$field_work_age);
	}

	public function set_work_age($work_age) {
		$this->set_field_value(self::$field_work_age, $work_age);
	}

	public function get_work_shares() {
		return $this->get_field_value(self::$field_work_shares);
	}

	public function set_work_shares($work_shares) {
		$this->set_field_value(self::$field_work_shares, $work_shares);
	}

	public function get_position_shares() {
		return $this->get_field_value(self::$field_position_shares);
	}

	public function set_position_shares($position_shares) {
		$this->set_field_value(self::$field_position_shares, $position_shares);
	}

	public function get_sanction_shares() {
		return $this->get_field_value(self::$field_sanction_shares);
	}

	public function set_sanction_shares($sanction_shares) {
		$this->set_field_value(self::$field_sanction_shares, $sanction_shares);
	}

	public function get_current_shares() {
		return $this->get_field_value(self::$field_current_shares);
	}

	public function set_current_shares($current_shares) {
		$this->set_field_value(self::$field_current_shares, $current_shares);
	}

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function get_remark() {
		return $this->get_field_value(self::$field_remark);
	}

	public function set_remark($remark) {
		$this->set_field_value(self::$field_remark, $remark);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_employee_no['name']]);
		//unset($arr[self::$field_username['name']]);
		//unset($arr[self::$field_dept1_id['name']]);
		//unset($arr[self::$field_dept2_id['name']]);
		//unset($arr[self::$field_role_id['name']]);
		//unset($arr[self::$field_join_time['name']]);
		//unset($arr[self::$field_join_days['name']]);
		//unset($arr[self::$field_work_age['name']]);
		//unset($arr[self::$field_work_shares['name']]);
		//unset($arr[self::$field_position_shares['name']]);
		//unset($arr[self::$field_sanction_shares['name']]);
		//unset($arr[self::$field_current_shares['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_remark['name']]);
		return $arr;
	}

}

M_Share::init_schema();
