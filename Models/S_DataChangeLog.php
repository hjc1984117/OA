<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/S_DataChangeLog.php'] = 1;

use Models\Base\Model;

class S_DataChangeLog extends Model{

	public static $field_id;
	public static $field_obj_id;
	public static $field_type;
	public static $field_addtime;
	public static $field_userid;
	public static $field_username;
	public static $field_role_type;
	public static $field_role_id;
	public static $field_comment;
	public static $field_old_data;
	public static $field_new_data;
	public static $field_changed_desc;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_obj_id = Model::define_field('obj_id', 'int', 0);
		self::$field_type = Model::define_field('type', 'int', 0);
		self::$field_addtime = Model::define_field('addtime', 'datetime', NULL);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$field_username = Model::define_field('username', 'string', NULL);
		self::$field_role_type = Model::define_field('role_type', 'int', 0);
		self::$field_role_id = Model::define_field('role_id', 'int', 0);
		self::$field_comment = Model::define_field('comment', 'string', NULL);
		self::$field_old_data = Model::define_field('old_data', 'string', NULL);
		self::$field_new_data = Model::define_field('new_data', 'string', NULL);
		self::$field_changed_desc = Model::define_field('changed_desc', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('S_DataChangeLog', array(
			self::$field_id,
			self::$field_obj_id,
			self::$field_type,
			self::$field_addtime,
			self::$field_userid,
			self::$field_username,
			self::$field_role_type,
			self::$field_role_id,
			self::$field_comment,
			self::$field_old_data,
			self::$field_new_data,
			self::$field_changed_desc
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_obj_id() {
		return $this->get_field_value(self::$field_obj_id);
	}

	public function set_obj_id($obj_id) {
		$this->set_field_value(self::$field_obj_id, $obj_id);
	}

	public function get_type() {
		return $this->get_field_value(self::$field_type);
	}

	public function set_type($type) {
		$this->set_field_value(self::$field_type, $type);
	}

	public function get_addtime() {
		return $this->get_field_value(self::$field_addtime);
	}

	public function set_addtime($addtime) {
		$this->set_field_value(self::$field_addtime, $addtime);
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

	public function get_role_type() {
		return $this->get_field_value(self::$field_role_type);
	}

	public function set_role_type($role_type) {
		$this->set_field_value(self::$field_role_type, $role_type);
	}

	public function get_role_id() {
		return $this->get_field_value(self::$field_role_id);
	}

	public function set_role_id($role_id) {
		$this->set_field_value(self::$field_role_id, $role_id);
	}

	public function get_comment() {
		return $this->get_field_value(self::$field_comment);
	}

	public function set_comment($comment) {
		$this->set_field_value(self::$field_comment, $comment);
	}

	public function get_old_data() {
		return $this->get_field_value(self::$field_old_data);
	}

	public function set_old_data($old_data) {
		$this->set_field_value(self::$field_old_data, $old_data);
	}

	public function get_new_data() {
		return $this->get_field_value(self::$field_new_data);
	}

	public function set_new_data($new_data) {
		$this->set_field_value(self::$field_new_data, $new_data);
	}

	public function get_changed_desc() {
		return $this->get_field_value(self::$field_changed_desc);
	}

	public function set_changed_desc($changed_desc) {
		$this->set_field_value(self::$field_changed_desc, $changed_desc);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_obj_id['name']]);
		//unset($arr[self::$field_type['name']]);
		//unset($arr[self::$field_addtime['name']]);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_username['name']]);
		//unset($arr[self::$field_role_type['name']]);
		//unset($arr[self::$field_role_id['name']]);
		//unset($arr[self::$field_comment['name']]);
		//unset($arr[self::$field_old_data['name']]);
		//unset($arr[self::$field_new_data['name']]);
		//unset($arr[self::$field_changed_desc['name']]);
		return $arr;
	}

}

S_DataChangeLog::init_schema();
