<?php

/**
* 每周工作内容
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/W_WeekWork.php'] = 1;

use Models\Base\Model;

class W_WeekWork extends Model{

	public static $field_id;
	public static $field_dept1_id;
	public static $field_dept2_id;
	public static $field_user_name;
	public static $field_user_id;
	public static $field_role_id;
	public static $field_insert_time;
	public static $field_working_content;
	public static $field_work_plan;
	public static $field_summary;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_dept1_id = Model::define_field('dept1_id', 'int', 0);
		self::$field_dept2_id = Model::define_field('dept2_id', 'int', 0);
		self::$field_user_name = Model::define_field('user_name', 'string', NULL);
		self::$field_user_id = Model::define_field('user_id', 'string', NULL);
		self::$field_role_id = Model::define_primary_key('role_id', 'string', NULL, false);
		self::$field_insert_time = Model::define_field('insert_time', 'date', NULL);
		self::$field_working_content = Model::define_field('working_content', 'string', NULL);
		self::$field_work_plan = Model::define_field('work_plan', 'string', NULL);
		self::$field_summary = Model::define_field('summary', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('W_WeekWork', array(
			self::$field_id,
			self::$field_dept1_id,
			self::$field_dept2_id,
			self::$field_user_name,
			self::$field_user_id,
			self::$field_role_id,
			self::$field_insert_time,
			self::$field_working_content,
			self::$field_work_plan,
			self::$field_summary
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
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

	public function get_user_name() {
		return $this->get_field_value(self::$field_user_name);
	}

	public function set_user_name($user_name) {
		$this->set_field_value(self::$field_user_name, $user_name);
	}

	public function get_user_id() {
		return $this->get_field_value(self::$field_user_id);
	}

	public function set_user_id($user_id) {
		$this->set_field_value(self::$field_user_id, $user_id);
	}

	public function get_role_id() {
		return $this->get_field_value(self::$field_role_id);
	}

	public function set_role_id($role_id) {
		$this->set_field_value(self::$field_role_id, $role_id);
	}

	public function get_insert_time() {
		return $this->get_field_value(self::$field_insert_time);
	}

	public function set_insert_time($insert_time) {
		$this->set_field_value(self::$field_insert_time, $insert_time);
	}

	public function get_working_content() {
		return $this->get_field_value(self::$field_working_content);
	}

	public function set_working_content($working_content) {
		$this->set_field_value(self::$field_working_content, $working_content);
	}

	public function get_work_plan() {
		return $this->get_field_value(self::$field_work_plan);
	}

	public function set_work_plan($work_plan) {
		$this->set_field_value(self::$field_work_plan, $work_plan);
	}

	public function get_summary() {
		return $this->get_field_value(self::$field_summary);
	}

	public function set_summary($summary) {
		$this->set_field_value(self::$field_summary, $summary);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_dept1_id['name']]);
		//unset($arr[self::$field_dept2_id['name']]);
		//unset($arr[self::$field_user_name['name']]);
		//unset($arr[self::$field_user_id['name']]);
		//unset($arr[self::$field_role_id['name']]);
		//unset($arr[self::$field_insert_time['name']]);
		//unset($arr[self::$field_working_content['name']]);
		//unset($arr[self::$field_work_plan['name']]);
		//unset($arr[self::$field_summary['name']]);
		return $arr;
	}

}

W_WeekWork::init_schema();
