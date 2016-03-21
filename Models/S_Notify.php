<?php

/**
* 通知
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/S_Notify.php'] = 1;

use Models\Base\Model;

class S_Notify extends Model{

	public static $field_id;
	public static $field_title;
	public static $field_content;
	public static $field_addtime;
	public static $field_dept1_id;
	public static $field_username;
	public static $field_userid;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_title = Model::define_field('title', 'string', NULL);
		self::$field_content = Model::define_field('content', 'string', NULL);
		self::$field_addtime = Model::define_field('addtime', 'datetime', NULL);
		self::$field_dept1_id = Model::define_field('dept1_id', 'int', 0);
		self::$field_username = Model::define_field('username', 'string', NULL);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$MODEL_SCHEMA = Model::build_schema('S_Notify', array(
			self::$field_id,
			self::$field_title,
			self::$field_content,
			self::$field_addtime,
			self::$field_dept1_id,
			self::$field_username,
			self::$field_userid
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

	public function get_content() {
		return $this->get_field_value(self::$field_content);
	}

	public function set_content($content) {
		$this->set_field_value(self::$field_content, $content);
	}

	public function get_addtime() {
		return $this->get_field_value(self::$field_addtime);
	}

	public function set_addtime($addtime) {
		$this->set_field_value(self::$field_addtime, $addtime);
	}

	public function get_dept1_id() {
		return $this->get_field_value(self::$field_dept1_id);
	}

	public function set_dept1_id($dept1_id) {
		$this->set_field_value(self::$field_dept1_id, $dept1_id);
	}

	public function get_username() {
		return $this->get_field_value(self::$field_username);
	}

	public function set_username($username) {
		$this->set_field_value(self::$field_username, $username);
	}

	public function get_userid() {
		return $this->get_field_value(self::$field_userid);
	}

	public function set_userid($userid) {
		$this->set_field_value(self::$field_userid, $userid);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_title['name']]);
		//unset($arr[self::$field_content['name']]);
		//unset($arr[self::$field_addtime['name']]);
		//unset($arr[self::$field_dept1_id['name']]);
		//unset($arr[self::$field_username['name']]);
		//unset($arr[self::$field_userid['name']]);
		return $arr;
	}

}

S_Notify::init_schema();
