<?php

/**
* 领料系统
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/C_Supply.php'] = 1;

use Models\Base\Model;

class C_Supply extends Model{

	public static $field_id;
	public static $field_addtime;
	public static $field_goods;
	public static $field_goods_id;
	public static $field_num;
	public static $field_price;
	public static $field_unit_price;
	public static $field_dept1_id;
	public static $field_dept2_id;
	public static $field_userid;
	public static $field_username;
	public static $field_status;
	public static $field_remarks;
	public static $field_way;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_addtime = Model::define_field('addtime', 'datetime', NULL);
		self::$field_goods = Model::define_field('goods', 'string', NULL);
		self::$field_goods_id = Model::define_field('goods_id', 'int', 0);
		self::$field_num = Model::define_field('num', 'int', 0);
		self::$field_price = Model::define_field('price', 'float', 0.00);
		self::$field_unit_price = Model::define_field('unit_price', 'float', 0.00);
		self::$field_dept1_id = Model::define_field('dept1_id', 'int', 0);
		self::$field_dept2_id = Model::define_field('dept2_id', 'int', 0);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$field_username = Model::define_field('username', 'string', NULL);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$field_remarks = Model::define_field('remarks', 'string', NULL);
		self::$field_way = Model::define_field('way', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('C_Supply', array(
			self::$field_id,
			self::$field_addtime,
			self::$field_goods,
			self::$field_goods_id,
			self::$field_num,
			self::$field_price,
			self::$field_unit_price,
			self::$field_dept1_id,
			self::$field_dept2_id,
			self::$field_userid,
			self::$field_username,
			self::$field_status,
			self::$field_remarks,
			self::$field_way
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

	public function get_goods() {
		return $this->get_field_value(self::$field_goods);
	}

	public function set_goods($goods) {
		$this->set_field_value(self::$field_goods, $goods);
	}

	public function get_goods_id() {
		return $this->get_field_value(self::$field_goods_id);
	}

	public function set_goods_id($goods_id) {
		$this->set_field_value(self::$field_goods_id, $goods_id);
	}

	public function get_num() {
		return $this->get_field_value(self::$field_num);
	}

	public function set_num($num) {
		$this->set_field_value(self::$field_num, $num);
	}

	public function get_price() {
		return $this->get_field_value(self::$field_price);
	}

	public function set_price($price) {
		$this->set_field_value(self::$field_price, $price);
	}

	public function get_unit_price() {
		return $this->get_field_value(self::$field_unit_price);
	}

	public function set_unit_price($unit_price) {
		$this->set_field_value(self::$field_unit_price, $unit_price);
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

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function get_remarks() {
		return $this->get_field_value(self::$field_remarks);
	}

	public function set_remarks($remarks) {
		$this->set_field_value(self::$field_remarks, $remarks);
	}

	public function get_way() {
		return $this->get_field_value(self::$field_way);
	}

	public function set_way($way) {
		$this->set_field_value(self::$field_way, $way);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_addtime['name']]);
		//unset($arr[self::$field_goods['name']]);
		//unset($arr[self::$field_goods_id['name']]);
		//unset($arr[self::$field_num['name']]);
		//unset($arr[self::$field_price['name']]);
		//unset($arr[self::$field_unit_price['name']]);
		//unset($arr[self::$field_dept1_id['name']]);
		//unset($arr[self::$field_dept2_id['name']]);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_username['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_remarks['name']]);
		//unset($arr[self::$field_way['name']]);
		return $arr;
	}

}

C_Supply::init_schema();
