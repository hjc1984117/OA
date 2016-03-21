<?php

/**
* 装修业绩
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_decoration.php'] = 1;

use Models\Base\Model;

class p_decoration extends Model{

	public static $field_id;
	public static $field_is_edit;
	public static $field_add_time;
	public static $field_ww;
	public static $field_qq;
	public static $field_name;
	public static $field_phone;
	public static $field_decoration_packages;
	public static $field_decoration_price;
	public static $field_isArrears;
	public static $field_alipay_account;
	public static $field_payment_method;
	public static $field_customer;
	public static $field_customer_id;
	public static $field_rception;
	public static $field_rception_id;
	public static $field_remark;
	public static $field_tra_num;
	public static $field_status;
	public static $field_p_arrears;
	public static $field_isTe;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_is_edit = Model::define_field('is_edit', 'int', 0);
		self::$field_add_time = Model::define_field('add_time', 'datetime', NULL);
		self::$field_ww = Model::define_field('ww', 'string', NULL);
		self::$field_qq = Model::define_field('qq', 'string', NULL);
		self::$field_name = Model::define_field('name', 'string', NULL);
		self::$field_phone = Model::define_field('phone', 'string', NULL);
		self::$field_decoration_packages = Model::define_field('decoration_packages', 'string', NULL);
		self::$field_decoration_price = Model::define_field('decoration_price', 'float', 0.00);
		self::$field_isArrears = Model::define_field('isArrears', 'int', 0);
		self::$field_alipay_account = Model::define_field('alipay_account', 'string', NULL);
		self::$field_payment_method = Model::define_field('payment_method', 'string', NULL);
		self::$field_customer = Model::define_field('customer', 'string', NULL);
		self::$field_customer_id = Model::define_field('customer_id', 'int', 0);
		self::$field_rception = Model::define_field('rception', 'string', NULL);
		self::$field_rception_id = Model::define_field('rception_id', 'int', 0);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$field_tra_num = Model::define_field('tra_num', 'string', NULL);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$field_p_arrears = Model::define_field('p_arrears', 'float', 0.00);
		self::$field_isTe = Model::define_field('isTe', 'int', 0);
		self::$MODEL_SCHEMA = Model::build_schema('p_decoration', array(
			self::$field_id,
			self::$field_is_edit,
			self::$field_add_time,
			self::$field_ww,
			self::$field_qq,
			self::$field_name,
			self::$field_phone,
			self::$field_decoration_packages,
			self::$field_decoration_price,
			self::$field_isArrears,
			self::$field_alipay_account,
			self::$field_payment_method,
			self::$field_customer,
			self::$field_customer_id,
			self::$field_rception,
			self::$field_rception_id,
			self::$field_remark,
			self::$field_tra_num,
			self::$field_status,
			self::$field_p_arrears,
			self::$field_isTe
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_is_edit() {
		return $this->get_field_value(self::$field_is_edit);
	}

	public function set_is_edit($is_edit) {
		$this->set_field_value(self::$field_is_edit, $is_edit);
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

	public function get_phone() {
		return $this->get_field_value(self::$field_phone);
	}

	public function set_phone($phone) {
		$this->set_field_value(self::$field_phone, $phone);
	}

	public function get_decoration_packages() {
		return $this->get_field_value(self::$field_decoration_packages);
	}

	public function set_decoration_packages($decoration_packages) {
		$this->set_field_value(self::$field_decoration_packages, $decoration_packages);
	}

	public function get_decoration_price() {
		return $this->get_field_value(self::$field_decoration_price);
	}

	public function set_decoration_price($decoration_price) {
		$this->set_field_value(self::$field_decoration_price, $decoration_price);
	}

	public function get_isArrears() {
		return $this->get_field_value(self::$field_isArrears);
	}

	public function set_isArrears($isArrears) {
		$this->set_field_value(self::$field_isArrears, $isArrears);
	}

	public function get_alipay_account() {
		return $this->get_field_value(self::$field_alipay_account);
	}

	public function set_alipay_account($alipay_account) {
		$this->set_field_value(self::$field_alipay_account, $alipay_account);
	}

	public function get_payment_method() {
		return $this->get_field_value(self::$field_payment_method);
	}

	public function set_payment_method($payment_method) {
		$this->set_field_value(self::$field_payment_method, $payment_method);
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

	public function get_rception() {
		return $this->get_field_value(self::$field_rception);
	}

	public function set_rception($rception) {
		$this->set_field_value(self::$field_rception, $rception);
	}

	public function get_rception_id() {
		return $this->get_field_value(self::$field_rception_id);
	}

	public function set_rception_id($rception_id) {
		$this->set_field_value(self::$field_rception_id, $rception_id);
	}

	public function get_remark() {
		return $this->get_field_value(self::$field_remark);
	}

	public function set_remark($remark) {
		$this->set_field_value(self::$field_remark, $remark);
	}

	public function get_tra_num() {
		return $this->get_field_value(self::$field_tra_num);
	}

	public function set_tra_num($tra_num) {
		$this->set_field_value(self::$field_tra_num, $tra_num);
	}

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function get_p_arrears() {
		return $this->get_field_value(self::$field_p_arrears);
	}

	public function set_p_arrears($p_arrears) {
		$this->set_field_value(self::$field_p_arrears, $p_arrears);
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
		//unset($arr[self::$field_is_edit['name']]);
		//unset($arr[self::$field_add_time['name']]);
		//unset($arr[self::$field_ww['name']]);
		//unset($arr[self::$field_qq['name']]);
		//unset($arr[self::$field_name['name']]);
		//unset($arr[self::$field_phone['name']]);
		//unset($arr[self::$field_decoration_packages['name']]);
		//unset($arr[self::$field_decoration_price['name']]);
		//unset($arr[self::$field_isArrears['name']]);
		//unset($arr[self::$field_alipay_account['name']]);
		//unset($arr[self::$field_payment_method['name']]);
		//unset($arr[self::$field_customer['name']]);
		//unset($arr[self::$field_customer_id['name']]);
		//unset($arr[self::$field_rception['name']]);
		//unset($arr[self::$field_rception_id['name']]);
		//unset($arr[self::$field_remark['name']]);
		//unset($arr[self::$field_tra_num['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_p_arrears['name']]);
		//unset($arr[self::$field_isTe['name']]);
		return $arr;
	}

}

p_decoration::init_schema();
