<?php

/**
* 电话系统_软件部
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_phone_sys_soft.php'] = 1;

use Models\Base\Model;

class p_phone_sys_soft extends Model{

	public static $field_id;
	public static $field_addtime;
	public static $field_phone;
	public static $field_city;
	public static $field_area;
	public static $field_phone_type;
	public static $field_setmeal;
	public static $field_use_username;
	public static $field_bind_shop;
	public static $field_used;
	public static $field_is_arrearage;
	public static $field_au_name;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_addtime = Model::define_field('addtime', 'datetime', NULL);
		self::$field_phone = Model::define_field('phone', 'string', NULL);
		self::$field_city = Model::define_field('city', 'string', NULL);
		self::$field_area = Model::define_field('area', 'string', NULL);
		self::$field_phone_type = Model::define_field('phone_type', 'string', NULL);
		self::$field_setmeal = Model::define_field('setmeal', 'string', NULL);
		self::$field_use_username = Model::define_field('use_username', 'string', NULL);
		self::$field_bind_shop = Model::define_field('bind_shop', 'string', NULL);
		self::$field_used = Model::define_field('used', 'string', NULL);
		self::$field_is_arrearage = Model::define_field('is_arrearage', 'string', NULL);
		self::$field_au_name = Model::define_field('au_name', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('p_phone_sys_soft', array(
			self::$field_id,
			self::$field_addtime,
			self::$field_phone,
			self::$field_city,
			self::$field_area,
			self::$field_phone_type,
			self::$field_setmeal,
			self::$field_use_username,
			self::$field_bind_shop,
			self::$field_used,
			self::$field_is_arrearage,
			self::$field_au_name
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_addtime() {
		return $this->get_field_value(self::$field_addtime);
	}

	public function set_addtime($addtime) {
		$this->set_field_value(self::$field_addtime, $addtime);
	}

	public function get_phone() {
		return $this->get_field_value(self::$field_phone);
	}

	public function set_phone($phone) {
		$this->set_field_value(self::$field_phone, $phone);
	}

	public function get_city() {
		return $this->get_field_value(self::$field_city);
	}

	public function set_city($city) {
		$this->set_field_value(self::$field_city, $city);
	}

	public function get_area() {
		return $this->get_field_value(self::$field_area);
	}

	public function set_area($area) {
		$this->set_field_value(self::$field_area, $area);
	}

	public function get_phone_type() {
		return $this->get_field_value(self::$field_phone_type);
	}

	public function set_phone_type($phone_type) {
		$this->set_field_value(self::$field_phone_type, $phone_type);
	}

	public function get_setmeal() {
		return $this->get_field_value(self::$field_setmeal);
	}

	public function set_setmeal($setmeal) {
		$this->set_field_value(self::$field_setmeal, $setmeal);
	}

	public function get_use_username() {
		return $this->get_field_value(self::$field_use_username);
	}

	public function set_use_username($use_username) {
		$this->set_field_value(self::$field_use_username, $use_username);
	}

	public function get_bind_shop() {
		return $this->get_field_value(self::$field_bind_shop);
	}

	public function set_bind_shop($bind_shop) {
		$this->set_field_value(self::$field_bind_shop, $bind_shop);
	}

	public function get_used() {
		return $this->get_field_value(self::$field_used);
	}

	public function set_used($used) {
		$this->set_field_value(self::$field_used, $used);
	}

	public function get_is_arrearage() {
		return $this->get_field_value(self::$field_is_arrearage);
	}

	public function set_is_arrearage($is_arrearage) {
		$this->set_field_value(self::$field_is_arrearage, $is_arrearage);
	}

	public function get_au_name() {
		return $this->get_field_value(self::$field_au_name);
	}

	public function set_au_name($au_name) {
		$this->set_field_value(self::$field_au_name, $au_name);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_addtime['name']]);
		//unset($arr[self::$field_phone['name']]);
		//unset($arr[self::$field_city['name']]);
		//unset($arr[self::$field_area['name']]);
		//unset($arr[self::$field_phone_type['name']]);
		//unset($arr[self::$field_setmeal['name']]);
		//unset($arr[self::$field_use_username['name']]);
		//unset($arr[self::$field_bind_shop['name']]);
		//unset($arr[self::$field_used['name']]);
		//unset($arr[self::$field_is_arrearage['name']]);
		//unset($arr[self::$field_au_name['name']]);
		return $arr;
	}

}

p_phone_sys_soft::init_schema();
