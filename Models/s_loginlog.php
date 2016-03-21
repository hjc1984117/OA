<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/s_loginlog.php'] = 1;

use Models\Base\Model;

class s_loginlog extends Model{

	public static $field_id;
	public static $field_userid;
	public static $field_date_time;
	public static $field_login_ip;
	public static $field_login_address;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$field_date_time = Model::define_field('date_time', 'datetime', NULL);
		self::$field_login_ip = Model::define_field('login_ip', 'string', '0:0:0:0');
		self::$field_login_address = Model::define_field('login_address', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('s_loginlog', array(
			self::$field_id,
			self::$field_userid,
			self::$field_date_time,
			self::$field_login_ip,
			self::$field_login_address
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

	public function get_date_time() {
		return $this->get_field_value(self::$field_date_time);
	}

	public function set_date_time($date_time) {
		$this->set_field_value(self::$field_date_time, $date_time);
	}

	public function get_login_ip() {
		return $this->get_field_value(self::$field_login_ip);
	}

	public function set_login_ip($login_ip) {
		$this->set_field_value(self::$field_login_ip, $login_ip);
	}

	public function get_login_address() {
		return $this->get_field_value(self::$field_login_address);
	}

	public function set_login_address($login_address) {
		$this->set_field_value(self::$field_login_address, $login_address);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_date_time['name']]);
		//unset($arr[self::$field_login_ip['name']]);
		//unset($arr[self::$field_login_address['name']]);
		return $arr;
	}

}

s_loginlog::init_schema();
