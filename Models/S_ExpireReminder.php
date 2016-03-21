<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/S_ExpireReminder.php'] = 1;

use Models\Base\Model;

class S_ExpireReminder extends Model{

	public static $field_id;
	public static $field_p_id;
	public static $field_type;
	public static $field_useed;
	public static $field_dueDate;
	public static $field_account;
	public static $field_remark;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_p_id = Model::define_field('p_id', 'int', 0);
		self::$field_type = Model::define_field('type', 'string', NULL);
		self::$field_useed = Model::define_field('useed', 'string', NULL);
		self::$field_dueDate = Model::define_field('dueDate', 'date', NULL);
		self::$field_account = Model::define_field('account', 'string', NULL);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('S_ExpireReminder', array(
			self::$field_id,
			self::$field_p_id,
			self::$field_type,
			self::$field_useed,
			self::$field_dueDate,
			self::$field_account,
			self::$field_remark
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_p_id() {
		return $this->get_field_value(self::$field_p_id);
	}

	public function set_p_id($p_id) {
		$this->set_field_value(self::$field_p_id, $p_id);
	}

	public function get_type() {
		return $this->get_field_value(self::$field_type);
	}

	public function set_type($type) {
		$this->set_field_value(self::$field_type, $type);
	}

	public function get_useed() {
		return $this->get_field_value(self::$field_useed);
	}

	public function set_useed($useed) {
		$this->set_field_value(self::$field_useed, $useed);
	}

	public function get_dueDate() {
		return $this->get_field_value(self::$field_dueDate);
	}

	public function set_dueDate($dueDate) {
		$this->set_field_value(self::$field_dueDate, $dueDate);
	}

	public function get_account() {
		return $this->get_field_value(self::$field_account);
	}

	public function set_account($account) {
		$this->set_field_value(self::$field_account, $account);
	}

	public function get_remark() {
		return $this->get_field_value(self::$field_remark);
	}

	public function set_remark($remark) {
		$this->set_field_value(self::$field_remark, $remark);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_p_id['name']]);
		//unset($arr[self::$field_type['name']]);
		//unset($arr[self::$field_useed['name']]);
		//unset($arr[self::$field_dueDate['name']]);
		//unset($arr[self::$field_account['name']]);
		//unset($arr[self::$field_remark['name']]);
		return $arr;
	}

}

S_ExpireReminder::init_schema();
