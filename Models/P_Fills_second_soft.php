<?php

/**
* 补欠款
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_fills_second_soft.php'] = 1;

use Models\Base\Model;

class p_fills_second_soft extends Model{

	public static $field_id;
	public static $field_type;
	public static $field_add_time;
	public static $field_ww;
	public static $field_qq;
	public static $field_name;
	public static $field_mobile;
	public static $field_play_price;
	public static $field_fill_sum;
	public static $field_customer;
	public static $field_customer_id;
	public static $field_platform_rception;
	public static $field_platform_rception_id;
	public static $field_add_name;
	public static $field_add_name_id;
	public static $field_parent_id;
	public static $field_remark;
	public static $field_payment_method;
	public static $field_tra_num;
	public static $field_isTe;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_type = Model::define_field('type', 'int', 0);
		self::$field_add_time = Model::define_field('add_time', 'datetime', NULL);
		self::$field_ww = Model::define_field('ww', 'string', NULL);
		self::$field_qq = Model::define_field('qq', 'string', NULL);
		self::$field_name = Model::define_field('name', 'string', NULL);
		self::$field_mobile = Model::define_field('mobile', 'string', NULL);
		self::$field_play_price = Model::define_field('play_price', 'float', 0.00);
		self::$field_fill_sum = Model::define_field('fill_sum', 'float', 0.00);
		self::$field_customer = Model::define_field('customer', 'string', NULL);
		self::$field_customer_id = Model::define_field('customer_id', 'int', 0);
		self::$field_platform_rception = Model::define_field('platform_rception', 'string', NULL);
		self::$field_platform_rception_id = Model::define_field('platform_rception_id', 'int', 0);
		self::$field_add_name = Model::define_field('add_name', 'string', NULL);
		self::$field_add_name_id = Model::define_field('add_name_id', 'int', 0);
		self::$field_parent_id = Model::define_field('parent_id', 'int', 0);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$field_payment_method = Model::define_field('payment_method', 'string', NULL);
		self::$field_tra_num = Model::define_field('tra_num', 'string', NULL);
		self::$field_isTe = Model::define_field('isTe', 'int', 0);
		self::$MODEL_SCHEMA = Model::build_schema('p_fills_second_soft', array(
			self::$field_id,
			self::$field_type,
			self::$field_add_time,
			self::$field_ww,
			self::$field_qq,
			self::$field_name,
			self::$field_mobile,
			self::$field_play_price,
			self::$field_fill_sum,
			self::$field_customer,
			self::$field_customer_id,
			self::$field_platform_rception,
			self::$field_platform_rception_id,
			self::$field_add_name,
			self::$field_add_name_id,
			self::$field_parent_id,
			self::$field_remark,
			self::$field_payment_method,
			self::$field_tra_num,
			self::$field_isTe
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

	public function get_add_time() {
		return $this->get_field_value(self::$field_add_time);
	}

	public function set_add_time($add_time) {
		$this->set_field_value(self::$field_add_time, $add_time);
	}

	public function get_ww() {
		return $this->get_field_value(self::$field_ww);
	}

	public function set_ww($ww) {
		$this->set_field_value(self::$field_ww, $ww);
	}

	public function get_qq() {
		return $this->get_field_value(self::$field_qq);
	}

	public function set_qq($qq) {
		$this->set_field_value(self::$field_qq, $qq);
	}

	public function get_name() {
		return $this->get_field_value(self::$field_name);
	}

	public function set_name($name) {
		$this->set_field_value(self::$field_name, $name);
	}

	public function get_mobile() {
		return $this->get_field_value(self::$field_mobile);
	}

	public function set_mobile($mobile) {
		$this->set_field_value(self::$field_mobile, $mobile);
	}

	public function get_play_price() {
		return $this->get_field_value(self::$field_play_price);
	}

	public function set_play_price($play_price) {
		$this->set_field_value(self::$field_play_price, $play_price);
	}

	public function get_fill_sum() {
		return $this->get_field_value(self::$field_fill_sum);
	}

	public function set_fill_sum($fill_sum) {
		$this->set_field_value(self::$field_fill_sum, $fill_sum);
	}

	public function get_customer() {
		return $this->get_field_value(self::$field_customer);
	}

	public function set_customer($customer) {
		$this->set_field_value(self::$field_customer, $customer);
	}

	public function get_customer_id() {
		return $this->get_field_value(self::$field_customer_id);
	}

	public function set_customer_id($customer_id) {
		$this->set_field_value(self::$field_customer_id, $customer_id);
	}

	public function get_platform_rception() {
		return $this->get_field_value(self::$field_platform_rception);
	}

	public function set_platform_rception($platform_rception) {
		$this->set_field_value(self::$field_platform_rception, $platform_rception);
	}

	public function get_platform_rception_id() {
		return $this->get_field_value(self::$field_platform_rception_id);
	}

	public function set_platform_rception_id($platform_rception_id) {
		$this->set_field_value(self::$field_platform_rception_id, $platform_rception_id);
	}

	public function get_add_name() {
		return $this->get_field_value(self::$field_add_name);
	}

	public function set_add_name($add_name) {
		$this->set_field_value(self::$field_add_name, $add_name);
	}

	public function get_add_name_id() {
		return $this->get_field_value(self::$field_add_name_id);
	}

	public function set_add_name_id($add_name_id) {
		$this->set_field_value(self::$field_add_name_id, $add_name_id);
	}

	public function get_parent_id() {
		return $this->get_field_value(self::$field_parent_id);
	}

	public function set_parent_id($parent_id) {
		$this->set_field_value(self::$field_parent_id, $parent_id);
	}

	public function get_remark() {
		return $this->get_field_value(self::$field_remark);
	}

	public function set_remark($remark) {
		$this->set_field_value(self::$field_remark, $remark);
	}

	public function get_payment_method() {
		return $this->get_field_value(self::$field_payment_method);
	}

	public function set_payment_method($payment_method) {
		$this->set_field_value(self::$field_payment_method, $payment_method);
	}

	public function get_tra_num() {
		return $this->get_field_value(self::$field_tra_num);
	}

	public function set_tra_num($tra_num) {
		$this->set_field_value(self::$field_tra_num, $tra_num);
	}

	public function get_isTe() {
		return $this->get_field_value(self::$field_isTe);
	}

	public function set_isTe($isTe) {
		$this->set_field_value(self::$field_isTe, $isTe);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_type['name']]);
		//unset($arr[self::$field_add_time['name']]);
		//unset($arr[self::$field_ww['name']]);
		//unset($arr[self::$field_qq['name']]);
		//unset($arr[self::$field_name['name']]);
		//unset($arr[self::$field_mobile['name']]);
		//unset($arr[self::$field_play_price['name']]);
		//unset($arr[self::$field_fill_sum['name']]);
		//unset($arr[self::$field_customer['name']]);
		//unset($arr[self::$field_customer_id['name']]);
		//unset($arr[self::$field_platform_rception['name']]);
		//unset($arr[self::$field_platform_rception_id['name']]);
		//unset($arr[self::$field_add_name['name']]);
		//unset($arr[self::$field_add_name_id['name']]);
		//unset($arr[self::$field_parent_id['name']]);
		//unset($arr[self::$field_remark['name']]);
		//unset($arr[self::$field_payment_method['name']]);
		//unset($arr[self::$field_tra_num['name']]);
		//unset($arr[self::$field_isTe['name']]);
		return $arr;
	}

}

p_fills_second_soft::init_schema();
