<?php

/**
* 固定资产管理
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/C_Assets.php'] = 1;

use Models\Base\Model;

class C_Assets extends Model{

	public static $field_id;
	public static $field_goods_name;
	public static $field_configure;
	public static $field_dept1_id;
	public static $field_dept2_id;
	public static $field_name;
	public static $field_buy_date;
	public static $field_buy_price;
	public static $field_life;
	public static $field_depreciation_proportion;
	public static $field_now_value;
	public static $field_whether_use;
	public static $field_remark;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_goods_name = Model::define_field('goods_name', 'string', NULL);
		self::$field_configure = Model::define_field('configure', 'string', NULL);
		self::$field_dept1_id = Model::define_field('dept1_id', 'int', 0);
		self::$field_dept2_id = Model::define_field('dept2_id', 'int', 0);
		self::$field_name = Model::define_field('name', 'string', NULL);
		self::$field_buy_date = Model::define_field('buy_date', 'datetime', NULL);
		self::$field_buy_price = Model::define_field('buy_price', 'float', 0.00);
		self::$field_life = Model::define_field('life', 'int', 0);
		self::$field_depreciation_proportion = Model::define_field('depreciation_proportion', 'float', 0.00);
		self::$field_now_value = Model::define_field('now_value', 'float', 0.00);
		self::$field_whether_use = Model::define_field('whether_use', 'int', 0);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('C_Assets', array(
			self::$field_id,
			self::$field_goods_name,
			self::$field_configure,
			self::$field_dept1_id,
			self::$field_dept2_id,
			self::$field_name,
			self::$field_buy_date,
			self::$field_buy_price,
			self::$field_life,
			self::$field_depreciation_proportion,
			self::$field_now_value,
			self::$field_whether_use,
			self::$field_remark
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_goods_name() {
		return $this->get_field_value(self::$field_goods_name);
	}

	public function set_goods_name($goods_name) {
		$this->set_field_value(self::$field_goods_name, $goods_name);
	}

	public function get_configure() {
		return $this->get_field_value(self::$field_configure);
	}

	public function set_configure($configure) {
		$this->set_field_value(self::$field_configure, $configure);
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

	public function get_name() {
		return $this->get_field_value(self::$field_name);
	}

	public function set_name($name) {
		$this->set_field_value(self::$field_name, $name);
	}

	public function get_buy_date() {
		return $this->get_field_value(self::$field_buy_date);
	}

	public function set_buy_date($buy_date) {
		$this->set_field_value(self::$field_buy_date, $buy_date);
	}

	public function get_buy_price() {
		return $this->get_field_value(self::$field_buy_price);
	}

	public function set_buy_price($buy_price) {
		$this->set_field_value(self::$field_buy_price, $buy_price);
	}

	public function get_life() {
		return $this->get_field_value(self::$field_life);
	}

	public function set_life($life) {
		$this->set_field_value(self::$field_life, $life);
	}

	public function get_depreciation_proportion() {
		return $this->get_field_value(self::$field_depreciation_proportion);
	}

	public function set_depreciation_proportion($depreciation_proportion) {
		$this->set_field_value(self::$field_depreciation_proportion, $depreciation_proportion);
	}

	public function get_now_value() {
		return $this->get_field_value(self::$field_now_value);
	}

	public function set_now_value($now_value) {
		$this->set_field_value(self::$field_now_value, $now_value);
	}

	public function get_whether_use() {
		return $this->get_field_value(self::$field_whether_use);
	}

	public function set_whether_use($whether_use) {
		$this->set_field_value(self::$field_whether_use, $whether_use);
	}

	public function get_remark() {
		return $this->get_field_value(self::$field_remark);
	}

	public function set_remark($remark) {
		$this->set_field_value(self::$field_remark, $remark);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_goods_name['name']]);
		//unset($arr[self::$field_configure['name']]);
		//unset($arr[self::$field_dept1_id['name']]);
		//unset($arr[self::$field_dept2_id['name']]);
		//unset($arr[self::$field_name['name']]);
		//unset($arr[self::$field_buy_date['name']]);
		//unset($arr[self::$field_buy_price['name']]);
		//unset($arr[self::$field_life['name']]);
		//unset($arr[self::$field_depreciation_proportion['name']]);
		//unset($arr[self::$field_now_value['name']]);
		//unset($arr[self::$field_whether_use['name']]);
		//unset($arr[self::$field_remark['name']]);
		return $arr;
	}

}

C_Assets::init_schema();
