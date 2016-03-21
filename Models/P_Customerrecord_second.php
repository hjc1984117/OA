<?php

/**
* 售后名单(二次销售)
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/P_Customerrecord_second.php'] = 1;

use Models\Base\Model;

class P_Customerrecord_second extends Model{

	public static $field_id;
	public static $field_type;
	public static $field_userid;
	public static $field_username;
	public static $field_nickname;
	public static $field_toplimit;
	public static $field_finish;
	public static $field_status;
	public static $field_qqReception;
	public static $field_tmallReception;
	public static $field_starttime;
	public static $field_endtime;
	public static $field_lastDistribution;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_type = Model::define_field('type', 'int', 0);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$field_username = Model::define_field('username', 'string', NULL);
		self::$field_nickname = Model::define_field('nickname', 'string', NULL);
		self::$field_toplimit = Model::define_field('toplimit', 'int', 0);
		self::$field_finish = Model::define_field('finish', 'int', 0);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$field_qqReception = Model::define_field('qqReception', 'int', 0);
		self::$field_tmallReception = Model::define_field('tmallReception', 'int', 0);
		self::$field_starttime = Model::define_field('starttime', 'string', NULL);
		self::$field_endtime = Model::define_field('endtime', 'string', NULL);
		self::$field_lastDistribution = Model::define_field('lastDistribution', 'string', 0);
		self::$MODEL_SCHEMA = Model::build_schema('P_Customerrecord_second', array(
			self::$field_id,
			self::$field_type,
			self::$field_userid,
			self::$field_username,
			self::$field_nickname,
			self::$field_toplimit,
			self::$field_finish,
			self::$field_status,
			self::$field_qqReception,
			self::$field_tmallReception,
			self::$field_starttime,
			self::$field_endtime,
			self::$field_lastDistribution
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_type() {
		return $this->get_field_value(self::$field_type);
	}

	public function set_type($type) {
		$this->set_field_value(self::$field_type, $type);
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

	public function get_nickname() {
		return $this->get_field_value(self::$field_nickname);
	}

	public function set_nickname($nickname) {
		$this->set_field_value(self::$field_nickname, $nickname);
	}

	public function get_toplimit() {
		return $this->get_field_value(self::$field_toplimit);
	}

	public function set_toplimit($toplimit) {
		$this->set_field_value(self::$field_toplimit, $toplimit);
	}

	public function get_finish() {
		return $this->get_field_value(self::$field_finish);
	}

	public function set_finish($finish) {
		$this->set_field_value(self::$field_finish, $finish);
	}

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function get_qqReception() {
		return $this->get_field_value(self::$field_qqReception);
	}

	public function set_qqReception($qqReception) {
		$this->set_field_value(self::$field_qqReception, $qqReception);
	}

	public function get_tmallReception() {
		return $this->get_field_value(self::$field_tmallReception);
	}

	public function set_tmallReception($tmallReception) {
		$this->set_field_value(self::$field_tmallReception, $tmallReception);
	}

	public function get_starttime() {
		return $this->get_field_value(self::$field_starttime);
	}

	public function set_starttime($starttime) {
		$this->set_field_value(self::$field_starttime, $starttime);
	}

	public function get_endtime() {
		return $this->get_field_value(self::$field_endtime);
	}

	public function set_endtime($endtime) {
		$this->set_field_value(self::$field_endtime, $endtime);
	}

	public function get_lastDistribution() {
		return $this->get_field_value(self::$field_lastDistribution);
	}

	public function set_lastDistribution($lastDistribution) {
		$this->set_field_value(self::$field_lastDistribution, $lastDistribution);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_type['name']]);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_username['name']]);
		//unset($arr[self::$field_nickname['name']]);
		//unset($arr[self::$field_toplimit['name']]);
		//unset($arr[self::$field_finish['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_qqReception['name']]);
		//unset($arr[self::$field_tmallReception['name']]);
		//unset($arr[self::$field_starttime['name']]);
		//unset($arr[self::$field_endtime['name']]);
		//unset($arr[self::$field_lastDistribution['name']]);
		return $arr;
	}

}

P_Customerrecord_second::init_schema();
