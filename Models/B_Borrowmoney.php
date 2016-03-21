<?php

/**
* 借款单
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/B_Borrowmoney.php'] = 1;

use Models\Base\Model;

class B_Borrowmoney extends Model{

	public static $field_id;
	public static $field_userid;
	public static $field_username;
	public static $field_addtime;
	public static $field_edate;
	public static $field_dept1_id;
	public static $field_dept2_id;
	public static $field_reason;
	public static $field_money;
	public static $field_remark;
	public static $field_manager;
	public static $field_manager_name;
	public static $field_top_manager;
	public static $field_top_manager_name;
	public static $field_finance;
	public static $field_finance_name;
	public static $field_status;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$field_username = Model::define_field('username', 'string', NULL);
		self::$field_addtime = Model::define_field('addtime', 'datetime', NULL);
		self::$field_edate = Model::define_field('edate', 'date', NULL);
		self::$field_dept1_id = Model::define_field('dept1_id', 'int', 0);
		self::$field_dept2_id = Model::define_field('dept2_id', 'int', 0);
		self::$field_reason = Model::define_field('reason', 'string', NULL);
		self::$field_money = Model::define_field('money', 'float', 0.00);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$field_manager = Model::define_field('manager', 'int', 0);
		self::$field_manager_name = Model::define_field('manager_name', 'string', NULL);
		self::$field_top_manager = Model::define_field('top_manager', 'int', 0);
		self::$field_top_manager_name = Model::define_field('top_manager_name', 'string', NULL);
		self::$field_finance = Model::define_field('finance', 'int', 0);
		self::$field_finance_name = Model::define_field('finance_name', 'string', NULL);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$MODEL_SCHEMA = Model::build_schema('B_Borrowmoney', array(
			self::$field_id,
			self::$field_userid,
			self::$field_username,
			self::$field_addtime,
			self::$field_edate,
			self::$field_dept1_id,
			self::$field_dept2_id,
			self::$field_reason,
			self::$field_money,
			self::$field_remark,
			self::$field_manager,
			self::$field_manager_name,
			self::$field_top_manager,
			self::$field_top_manager_name,
			self::$field_finance,
			self::$field_finance_name,
			self::$field_status
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

	public function get_addtime() {
		return $this->get_field_value(self::$field_addtime);
	}

	public function set_addtime($addtime) {
		$this->set_field_value(self::$field_addtime, $addtime);
	}

	public function get_edate() {
		return $this->get_field_value(self::$field_edate);
	}

	public function set_edate($edate) {
		$this->set_field_value(self::$field_edate, $edate);
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

	public function get_money() {
		return $this->get_field_value(self::$field_money);
	}

	public function set_money($money) {
		$this->set_field_value(self::$field_money, $money);
	}

	public function get_remark() {
		return $this->get_field_value(self::$field_remark);
	}

	public function set_remark($remark) {
		$this->set_field_value(self::$field_remark, $remark);
	}

	public function get_manager() {
		return $this->get_field_value(self::$field_manager);
	}

	public function set_manager($manager) {
		$this->set_field_value(self::$field_manager, $manager);
	}

	public function get_manager_name() {
		return $this->get_field_value(self::$field_manager_name);
	}

	public function set_manager_name($manager_name) {
		$this->set_field_value(self::$field_manager_name, $manager_name);
	}

	public function get_top_manager() {
		return $this->get_field_value(self::$field_top_manager);
	}

	public function set_top_manager($top_manager) {
		$this->set_field_value(self::$field_top_manager, $top_manager);
	}

	public function get_top_manager_name() {
		return $this->get_field_value(self::$field_top_manager_name);
	}

	public function set_top_manager_name($top_manager_name) {
		$this->set_field_value(self::$field_top_manager_name, $top_manager_name);
	}

	public function get_finance() {
		return $this->get_field_value(self::$field_finance);
	}

	public function set_finance($finance) {
		$this->set_field_value(self::$field_finance, $finance);
	}

	public function get_finance_name() {
		return $this->get_field_value(self::$field_finance_name);
	}

	public function set_finance_name($finance_name) {
		$this->set_field_value(self::$field_finance_name, $finance_name);
	}

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_username['name']]);
		//unset($arr[self::$field_addtime['name']]);
		//unset($arr[self::$field_edate['name']]);
		//unset($arr[self::$field_dept1_id['name']]);
		//unset($arr[self::$field_dept2_id['name']]);
		//unset($arr[self::$field_reason['name']]);
		//unset($arr[self::$field_money['name']]);
		//unset($arr[self::$field_remark['name']]);
		//unset($arr[self::$field_manager['name']]);
		//unset($arr[self::$field_manager_name['name']]);
		//unset($arr[self::$field_top_manager['name']]);
		//unset($arr[self::$field_top_manager_name['name']]);
		//unset($arr[self::$field_finance['name']]);
		//unset($arr[self::$field_finance_name['name']]);
		//unset($arr[self::$field_status['name']]);
		return $arr;
	}

}

B_Borrowmoney::init_schema();
