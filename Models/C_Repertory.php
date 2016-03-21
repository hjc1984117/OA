<?php

/**
* 库存
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/C_Repertory.php'] = 1;

use Models\Base\Model;

class C_Repertory extends Model{

	public static $field_id;
	public static $field_total_count;
	public static $field_surplus_count;
	public static $field_goods_name;
	public static $field_unit_price;
	public static $field_remark;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_total_count = Model::define_field('total_count', 'int', 0);
		self::$field_surplus_count = Model::define_field('surplus_count', 'int', 0);
		self::$field_goods_name = Model::define_field('goods_name', 'string', NULL);
		self::$field_unit_price = Model::define_field('unit_price', 'float', 0.00);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('C_Repertory', array(
			self::$field_id,
			self::$field_total_count,
			self::$field_surplus_count,
			self::$field_goods_name,
			self::$field_unit_price,
			self::$field_remark
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_total_count() {
		return $this->get_field_value(self::$field_total_count);
	}

	public function set_total_count($total_count) {
		$this->set_field_value(self::$field_total_count, $total_count);
	}

	public function get_surplus_count() {
		return $this->get_field_value(self::$field_surplus_count);
	}

	public function set_surplus_count($surplus_count) {
		$this->set_field_value(self::$field_surplus_count, $surplus_count);
	}

	public function get_goods_name() {
		return $this->get_field_value(self::$field_goods_name);
	}

	public function set_goods_name($goods_name) {
		$this->set_field_value(self::$field_goods_name, $goods_name);
	}

	public function get_unit_price() {
		return $this->get_field_value(self::$field_unit_price);
	}

	public function set_unit_price($unit_price) {
		$this->set_field_value(self::$field_unit_price, $unit_price);
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
		//unset($arr[self::$field_total_count['name']]);
		//unset($arr[self::$field_surplus_count['name']]);
		//unset($arr[self::$field_goods_name['name']]);
		//unset($arr[self::$field_unit_price['name']]);
		//unset($arr[self::$field_remark['name']]);
		return $arr;
	}

}

C_Repertory::init_schema();
