<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/S_WorkflowLog.php'] = 1;

use Models\Base\Model;

class S_WorkflowLog extends Model{

	public static $field_id;
	public static $field_workflow_id;
	public static $field_type;
	public static $field_addtime;
	public static $field_userid;
	public static $field_username;
	public static $field_role_type;
	public static $field_role_id;
	public static $field_comment;
	public static $field_workflow_status;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_workflow_id = Model::define_field('workflow_id', 'int', 0);
		self::$field_type = Model::define_field('type', 'int', 0);
		self::$field_addtime = Model::define_field('addtime', 'datetime', NULL);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$field_username = Model::define_field('username', 'string', NULL);
		self::$field_role_type = Model::define_field('role_type', 'int', 0);
		self::$field_role_id = Model::define_field('role_id', 'int', 0);
		self::$field_comment = Model::define_field('comment', 'string', NULL);
		self::$field_workflow_status = Model::define_field('workflow_status', 'int', 0);
		self::$MODEL_SCHEMA = Model::build_schema('S_WorkflowLog', array(
			self::$field_id,
			self::$field_workflow_id,
			self::$field_type,
			self::$field_addtime,
			self::$field_userid,
			self::$field_username,
			self::$field_role_type,
			self::$field_role_id,
			self::$field_comment,
			self::$field_workflow_status
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_workflow_id() {
		return $this->get_field_value(self::$field_workflow_id);
	}

	public function set_workflow_id($workflow_id) {
		$this->set_field_value(self::$field_workflow_id, $workflow_id);
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

	public function get_workflow_status() {
		return $this->get_field_value(self::$field_workflow_status);
	}

	public function set_workflow_status($workflow_status) {
		$this->set_field_value(self::$field_workflow_status, $workflow_status);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_workflow_id['name']]);
		//unset($arr[self::$field_type['name']]);
		//unset($arr[self::$field_addtime['name']]);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_username['name']]);
		//unset($arr[self::$field_role_type['name']]);
		//unset($arr[self::$field_role_id['name']]);
		//unset($arr[self::$field_comment['name']]);
		//unset($arr[self::$field_workflow_status['name']]);
		return $arr;
	}

}

S_WorkflowLog::init_schema();
