<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/w_attuser.php'] = 1;

use Models\Base\Model;

class w_attuser extends Model{

	public static $field_atid;
	public static $field_bid;
	public static $field_aname;
	public static $field_uname;
	public static $field_userid;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_atid = Model::define_primary_key('atid', 'int', 0, false);
		self::$field_bid = Model::define_field('bid', 'int', 0);
		self::$field_aname = Model::define_field('aname', 'string', NULL);
		self::$field_uname = Model::define_field('uname', 'string', NULL);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$MODEL_SCHEMA = Model::build_schema('w_attuser', array(
			self::$field_atid,
			self::$field_bid,
			self::$field_aname,
			self::$field_uname,
			self::$field_userid
		));
	}


	public function get_atid() {
		return $this->get_field_value(self::$field_atid);
	}

	public function set_atid($atid) {
		$this->set_field_value(self::$field_atid, $atid);
	}

	public function get_bid() {
		return $this->get_field_value(self::$field_bid);
	}

	public function set_bid($bid) {
		$this->set_field_value(self::$field_bid, $bid);
	}

	public function get_aname() {
		return $this->get_field_value(self::$field_aname);
	}

	public function set_aname($aname) {
		$this->set_field_value(self::$field_aname, $aname);
	}

	public function get_uname() {
		return $this->get_field_value(self::$field_uname);
	}

	public function set_uname($uname) {
		$this->set_field_value(self::$field_uname, $uname);
	}

	public function get_userid() {
		return $this->get_field_value(self::$field_userid);
	}

	public function set_userid($userid) {
		$this->set_field_value(self::$field_userid, $userid);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_atid['name']]);
		//unset($arr[self::$field_bid['name']]);
		//unset($arr[self::$field_aname['name']]);
		//unset($arr[self::$field_uname['name']]);
		//unset($arr[self::$field_userid['name']]);
		return $arr;
	}

}

w_attuser::init_schema();
