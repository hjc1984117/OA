<?php

/**
* 岗位职责
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/W_Station.php'] = 1;

use Models\Base\Model;

class W_Station extends Model{

	public static $field_role_id;
	public static $field_role_text;
	public static $field_addtime;
	public static $field_dept1_id;
	public static $field_dept2_id;
	public static $field_pur;
	public static $field_station_key1;
	public static $field_demand;
	public static $field_exp;
	public static $field_credentials;
	public static $field_position;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_role_id = Model::define_primary_key('role_id', 'string', NULL, false);
		self::$field_role_text = Model::define_field('role_text', 'string', NULL);
		self::$field_addtime = Model::define_field('addtime', 'date', NULL);
		self::$field_dept1_id = Model::define_field('dept1_id', 'string', NULL);
		self::$field_dept2_id = Model::define_field('dept2_id', 'string', NULL);
		self::$field_pur = Model::define_field('pur', 'string', NULL);
		self::$field_station_key1 = Model::define_field('station_key1', 'string', NULL);
		self::$field_demand = Model::define_field('demand', 'string', NULL);
		self::$field_exp = Model::define_field('exp', 'string', NULL);
		self::$field_credentials = Model::define_field('credentials', 'string', NULL);
		self::$field_position = Model::define_field('position', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('W_Station', array(
			self::$field_role_id,
			self::$field_role_text,
			self::$field_addtime,
			self::$field_dept1_id,
			self::$field_dept2_id,
			self::$field_pur,
			self::$field_station_key1,
			self::$field_demand,
			self::$field_exp,
			self::$field_credentials,
			self::$field_position
		));
	}


	public function get_role_id() {
		return $this->get_field_value(self::$field_role_id);
	}

	public function set_role_id($role_id) {
		$this->set_field_value(self::$field_role_id, $role_id);
	}

	public function get_role_text() {
		return $this->get_field_value(self::$field_role_text);
	}

	public function set_role_text($role_text) {
		$this->set_field_value(self::$field_role_text, $role_text);
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

	public function get_dept2_id() {
		return $this->get_field_value(self::$field_dept2_id);
	}

	public function set_dept2_id($dept2_id) {
		$this->set_field_value(self::$field_dept2_id, $dept2_id);
	}

	public function get_pur() {
		return $this->get_field_value(self::$field_pur);
	}

	public function set_pur($pur) {
		$this->set_field_value(self::$field_pur, $pur);
	}

	public function get_station_key1() {
		return $this->get_field_value(self::$field_station_key1);
	}

	public function set_station_key1($station_key1) {
		$this->set_field_value(self::$field_station_key1, $station_key1);
	}

	public function get_demand() {
		return $this->get_field_value(self::$field_demand);
	}

	public function set_demand($demand) {
		$this->set_field_value(self::$field_demand, $demand);
	}

	public function get_exp() {
		return $this->get_field_value(self::$field_exp);
	}

	public function set_exp($exp) {
		$this->set_field_value(self::$field_exp, $exp);
	}

	public function get_credentials() {
		return $this->get_field_value(self::$field_credentials);
	}

	public function set_credentials($credentials) {
		$this->set_field_value(self::$field_credentials, $credentials);
	}

	public function get_position() {
		return $this->get_field_value(self::$field_position);
	}

	public function set_position($position) {
		$this->set_field_value(self::$field_position, $position);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_role_id['name']]);
		//unset($arr[self::$field_role_text['name']]);
		//unset($arr[self::$field_addtime['name']]);
		//unset($arr[self::$field_dept1_id['name']]);
		//unset($arr[self::$field_dept2_id['name']]);
		//unset($arr[self::$field_pur['name']]);
		//unset($arr[self::$field_station_key1['name']]);
		//unset($arr[self::$field_demand['name']]);
		//unset($arr[self::$field_exp['name']]);
		//unset($arr[self::$field_credentials['name']]);
		//unset($arr[self::$field_position['name']]);
		return $arr;
	}

}

W_Station::init_schema();
