<?php

/**
* 用户令牌
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/M_UserToken.php'] = 1;

use Models\Base\Model;

class M_UserToken extends Model{

	public static $field_userid;
	public static $field_employee_no;
	public static $field_username;
	public static $field_password;
	public static $field_token;
	public static $field_session_magic_mark;
	public static $field_status;
	public static $field_expire;
	public static $field_avatar;
	public static $field_exp;
	public static $field_settings;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_userid = Model::define_primary_key('userid', 'int', 0, false);
		self::$field_employee_no = Model::define_field('employee_no', 'string', NULL);
		self::$field_username = Model::define_field('username', 'string', NULL);
		self::$field_password = Model::define_field('password', 'string', NULL);
		self::$field_token = Model::define_field('token', 'string', NULL);
		self::$field_session_magic_mark = Model::define_field('session_magic_mark', 'string', NULL);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$field_expire = Model::define_field('expire', 'datetime', NULL);
		self::$field_avatar = Model::define_field('avatar', 'string', '../../upload_avatar/default.png');
		self::$field_exp = Model::define_field('exp', 'int', 0);
		self::$field_settings = Model::define_field('settings', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('M_UserToken', array(
			self::$field_userid,
			self::$field_employee_no,
			self::$field_username,
			self::$field_password,
			self::$field_token,
			self::$field_session_magic_mark,
			self::$field_status,
			self::$field_expire,
			self::$field_avatar,
			self::$field_exp,
			self::$field_settings
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

	public function get_password() {
		return $this->get_field_value(self::$field_password);
	}

	public function set_password($password) {
		$this->set_field_value(self::$field_password, $password);
	}

	public function get_token() {
		return $this->get_field_value(self::$field_token);
	}

	public function set_token($token) {
		$this->set_field_value(self::$field_token, $token);
	}

	public function get_session_magic_mark() {
		return $this->get_field_value(self::$field_session_magic_mark);
	}

	public function set_session_magic_mark($session_magic_mark) {
		$this->set_field_value(self::$field_session_magic_mark, $session_magic_mark);
	}

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function get_expire() {
		return $this->get_field_value(self::$field_expire);
	}

	public function set_expire($expire) {
		$this->set_field_value(self::$field_expire, $expire);
	}

	public function get_avatar() {
		return $this->get_field_value(self::$field_avatar);
	}

	public function set_avatar($avatar) {
		$this->set_field_value(self::$field_avatar, $avatar);
	}

	public function get_exp() {
		return $this->get_field_value(self::$field_exp);
	}

	public function set_exp($exp) {
		$this->set_field_value(self::$field_exp, $exp);
	}

	public function get_settings() {
		return $this->get_field_value(self::$field_settings);
	}

	public function set_settings($settings) {
		$this->set_field_value(self::$field_settings, $settings);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_employee_no['name']]);
		//unset($arr[self::$field_username['name']]);
		//unset($arr[self::$field_password['name']]);
		//unset($arr[self::$field_token['name']]);
		//unset($arr[self::$field_session_magic_mark['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_expire['name']]);
		//unset($arr[self::$field_avatar['name']]);
		//unset($arr[self::$field_exp['name']]);
		//unset($arr[self::$field_settings['name']]);
		return $arr;
	}

}

M_UserToken::init_schema();
