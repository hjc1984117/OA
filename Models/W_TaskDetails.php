<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/W_TaskDetails.php'] = 1;

use Models\Base\Model;

class W_TaskDetails extends Model{

	public static $field_id;
	public static $field_taskId;
	public static $field_content;
	public static $field_addUserId;
	public static $field_addUserName;
	public static $field_addTime;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_taskId = Model::define_field('taskId', 'int', 0);
		self::$field_content = Model::define_field('content', 'string', NULL);
		self::$field_addUserId = Model::define_field('addUserId', 'int', 0);
		self::$field_addUserName = Model::define_field('addUserName', 'string', NULL);
		self::$field_addTime = Model::define_field('addTime', 'datetime', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('W_TaskDetails', array(
			self::$field_id,
			self::$field_taskId,
			self::$field_content,
			self::$field_addUserId,
			self::$field_addUserName,
			self::$field_addTime
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_taskId() {
		return $this->get_field_value(self::$field_taskId);
	}

	public function set_taskId($taskId) {
		$this->set_field_value(self::$field_taskId, $taskId);
	}

	public function get_content() {
		return $this->get_field_value(self::$field_content);
	}

	public function set_content($content) {
		$this->set_field_value(self::$field_content, $content);
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

	public function get_addTime() {
		return $this->get_field_value(self::$field_addTime);
	}

	public function set_addTime($addTime) {
		$this->set_field_value(self::$field_addTime, $addTime);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_taskId['name']]);
		//unset($arr[self::$field_content['name']]);
		//unset($arr[self::$field_addUserId['name']]);
		//unset($arr[self::$field_addUserName['name']]);
		//unset($arr[self::$field_addTime['name']]);
		return $arr;
	}

}

W_TaskDetails::init_schema();
