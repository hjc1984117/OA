<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/W_Task.php'] = 1;

use Models\Base\Model;

class W_Task extends Model{

	public static $field_id;
	public static $field_title;
	public static $field_type;
	public static $field_status;
	public static $field_addUserId;
	public static $field_addUserName;
	public static $field_takeOverId;
	public static $field_takeOverName;
	public static $field_addTime;
	public static $field_startTime;
	public static $field_endTime;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_title = Model::define_field('title', 'string', NULL);
		self::$field_type = Model::define_field('type', 'int', 0);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$field_addUserId = Model::define_field('addUserId', 'int', 0);
		self::$field_addUserName = Model::define_field('addUserName', 'string', NULL);
		self::$field_takeOverId = Model::define_field('takeOverId', 'int', 0);
		self::$field_takeOverName = Model::define_field('takeOverName', 'string', NULL);
		self::$field_addTime = Model::define_field('addTime', 'datetime', NULL);
		self::$field_startTime = Model::define_field('startTime', 'datetime', NULL);
		self::$field_endTime = Model::define_field('endTime', 'datetime', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('W_Task', array(
			self::$field_id,
			self::$field_title,
			self::$field_type,
			self::$field_status,
			self::$field_addUserId,
			self::$field_addUserName,
			self::$field_takeOverId,
			self::$field_takeOverName,
			self::$field_addTime,
			self::$field_startTime,
			self::$field_endTime
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_title() {
		return $this->get_field_value(self::$field_title);
	}

	public function set_title($title) {
		$this->set_field_value(self::$field_title, $title);
	}

	public function get_type() {
		return $this->get_field_value(self::$field_type);
	}

	public function set_type($type) {
		$this->set_field_value(self::$field_type, $type);
	}

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function get_addUserId() {
		return $this->get_field_value(self::$field_addUserId);
	}

	public function set_addUserId($addUserId) {
		$this->set_field_value(self::$field_addUserId, $addUserId);
	}

	public function get_addUserName() {
		return $this->get_field_value(self::$field_addUserName);
	}

	public function set_addUserName($addUserName) {
		$this->set_field_value(self::$field_addUserName, $addUserName);
	}

	public function get_takeOverId() {
		return $this->get_field_value(self::$field_takeOverId);
	}

	public function set_takeOverId($takeOverId) {
		$this->set_field_value(self::$field_takeOverId, $takeOverId);
	}

	public function get_takeOverName() {
		return $this->get_field_value(self::$field_takeOverName);
	}

	public function set_takeOverName($takeOverName) {
		$this->set_field_value(self::$field_takeOverName, $takeOverName);
	}

	public function get_addTime() {
		return $this->get_field_value(self::$field_addTime);
	}

	public function set_addTime($addTime) {
		$this->set_field_value(self::$field_addTime, $addTime);
	}

	public function get_startTime() {
		return $this->get_field_value(self::$field_startTime);
	}

	public function set_startTime($startTime) {
		$this->set_field_value(self::$field_startTime, $startTime);
	}

	public function get_endTime() {
		return $this->get_field_value(self::$field_endTime);
	}

	public function set_endTime($endTime) {
		$this->set_field_value(self::$field_endTime, $endTime);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_title['name']]);
		//unset($arr[self::$field_type['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_addUserId['name']]);
		//unset($arr[self::$field_addUserName['name']]);
		//unset($arr[self::$field_takeOverId['name']]);
		//unset($arr[self::$field_takeOverName['name']]);
		//unset($arr[self::$field_addTime['name']]);
		//unset($arr[self::$field_startTime['name']]);
		//unset($arr[self::$field_endTime['name']]);
		return $arr;
	}

}

W_Task::init_schema();
