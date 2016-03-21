<?php

/**
* 职位
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/M_Role.php'] = 1;

use Models\Base\Model;

class M_Role extends Model{

	public static $field_id;
	public static $field_text;
	public static $field_permit;
	public static $field_dept1_id;
	public static $field_dept2_id;
	public static $field_shares;
	public static $field_role_type;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, false);
		self::$field_text = Model::define_field('text', 'string', NULL);
		self::$field_permit = Model::define_field('permit', 'string', NULL);
		self::$field_dept1_id = Model::define_field('dept1_id', 'int', 0);
		self::$field_dept2_id = Model::define_field('dept2_id', 'int', 0);
		self::$field_shares = Model::define_field('shares', 'float', 0.00);
		self::$field_role_type = Model::define_field('role_type', 'int', 0);
		self::$MODEL_SCHEMA = Model::build_schema('M_Role', array(
			self::$field_id,
			self::$field_text,
			self::$field_permit,
			self::$field_dept1_id,
			self::$field_dept2_id,
			self::$field_shares,
			self::$field_role_type
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_text() {
		return $this->get_field_value(self::$field_text);
	}

	public function set_text($text) {
		$this->set_field_value(self::$field_text, $text);
	}

	public function get_permit() {
		return $this->get_field_value(self::$field_permit);
	}

	public function set_permit($permit) {
		$this->set_field_value(self::$field_permit, $permit);
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

	public function get_shares() {
		return $this->get_field_value(self::$field_shares);
	}

	public function set_shares($shares) {
		$this->set_field_value(self::$field_shares, $shares);
	}

	public function get_role_type() {
		return $this->get_field_value(self::$field_role_type);
	}

	public function set_role_type($role_type) {
		$this->set_field_value(self::$field_role_type, $role_type);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_text['name']]);
		//unset($arr[self::$field_permit['name']]);
		//unset($arr[self::$field_dept1_id['name']]);
		//unset($arr[self::$field_dept2_id['name']]);
		//unset($arr[self::$field_shares['name']]);
		//unset($arr[self::$field_role_type['name']]);
		return $arr;
	}

}

M_Role::init_schema();
