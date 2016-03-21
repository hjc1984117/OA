<?php

/**
* 代运营
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_generationoperation_soft.php'] = 1;

use Models\Base\Model;

class p_generationoperation_soft extends Model{

	public static $field_id;
	public static $field_is_edit;
	public static $field_add_time;
	public static $field_platform_num;
	public static $field_qq;
	public static $field_sales_numbers;
	public static $field_payment_amount;
	public static $field_isArrears;
	public static $field_platform_sales;
	public static $field_platform_sales_id;
	public static $field_customer;
	public static $field_customer_id;
	public static $field_headmaster;
	public static $field_headmaster_id;
	public static $field_payment_method;
	public static $field_share_performance;
	public static $field_remark;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_is_edit = Model::define_field('is_edit', 'int', 0);
		self::$field_add_time = Model::define_field('add_time', 'datetime', NULL);
		self::$field_platform_num = Model::define_field('platform_num', 'string', NULL);
		self::$field_qq = Model::define_field('qq', 'string', NULL);
		self::$field_sales_numbers = Model::define_field('sales_numbers', 'int', 0);
		self::$field_payment_amount = Model::define_field('payment_amount', 'float', 0.00);
		self::$field_isArrears = Model::define_field('isArrears', 'int', 0);
		self::$field_platform_sales = Model::define_field('platform_sales', 'string', NULL);
		self::$field_platform_sales_id = Model::define_field('platform_sales_id', 'int', 0);
		self::$field_customer = Model::define_field('customer', 'string', NULL);
		self::$field_customer_id = Model::define_field('customer_id', 'int', 0);
		self::$field_headmaster = Model::define_field('headmaster', 'string', NULL);
		self::$field_headmaster_id = Model::define_field('headmaster_id', 'int', 0);
		self::$field_payment_method = Model::define_field('payment_method', 'string', NULL);
		self::$field_share_performance = Model::define_field('share_performance', 'float', 0.00);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('p_generationoperation_soft', array(
			self::$field_id,
			self::$field_is_edit,
			self::$field_add_time,
			self::$field_platform_num,
			self::$field_qq,
			self::$field_sales_numbers,
			self::$field_payment_amount,
			self::$field_isArrears,
			self::$field_platform_sales,
			self::$field_platform_sales_id,
			self::$field_customer,
			self::$field_customer_id,
			self::$field_headmaster,
			self::$field_headmaster_id,
			self::$field_payment_method,
			self::$field_share_performance,
			self::$field_remark
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

	public function get_platform_num() {
		return $this->get_field_value(self::$field_platform_num);
	}

	public function set_platform_num($platform_num) {
		$this->set_field_value(self::$field_platform_num, $platform_num);
	}

	public function get_qq() {
		return $this->get_field_value(self::$field_qq);
	}

	public function set_qq($qq) {
		$this->set_field_value(self::$field_qq, $qq);
	}

	public function get_sales_numbers() {
		return $this->get_field_value(self::$field_sales_numbers);
	}

	public function set_sales_numbers($sales_numbers) {
		$this->set_field_value(self::$field_sales_numbers, $sales_numbers);
	}

	public function get_payment_amount() {
		return $this->get_field_value(self::$field_payment_amount);
	}

	public function set_payment_amount($payment_amount) {
		$this->set_field_value(self::$field_payment_amount, $payment_amount);
	}

	public function get_isArrears() {
		return $this->get_field_value(self::$field_isArrears);
	}

	public function set_isArrears($isArrears) {
		$this->set_field_value(self::$field_isArrears, $isArrears);
	}

	public function get_platform_sales() {
		return $this->get_field_value(self::$field_platform_sales);
	}

	public function set_platform_sales($platform_sales) {
		$this->set_field_value(self::$field_platform_sales, $platform_sales);
	}

	public function get_platform_sales_id() {
		return $this->get_field_value(self::$field_platform_sales_id);
	}

	public function set_platform_sales_id($platform_sales_id) {
		$this->set_field_value(self::$field_platform_sales_id, $platform_sales_id);
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

	public function get_headmaster() {
		return $this->get_field_value(self::$field_headmaster);
	}

	public function set_headmaster($headmaster) {
		$this->set_field_value(self::$field_headmaster, $headmaster);
	}

	public function get_headmaster_id() {
		return $this->get_field_value(self::$field_headmaster_id);
	}

	public function set_headmaster_id($headmaster_id) {
		$this->set_field_value(self::$field_headmaster_id, $headmaster_id);
	}

	public function get_payment_method() {
		return $this->get_field_value(self::$field_payment_method);
	}

	public function set_payment_method($payment_method) {
		$this->set_field_value(self::$field_payment_method, $payment_method);
	}

	public function get_share_performance() {
		return $this->get_field_value(self::$field_share_performance);
	}

	public function set_share_performance($share_performance) {
		$this->set_field_value(self::$field_share_performance, $share_performance);
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
		//unset($arr[self::$field_is_edit['name']]);
		//unset($arr[self::$field_add_time['name']]);
		//unset($arr[self::$field_platform_num['name']]);
		//unset($arr[self::$field_qq['name']]);
		//unset($arr[self::$field_sales_numbers['name']]);
		//unset($arr[self::$field_payment_amount['name']]);
		//unset($arr[self::$field_isArrears['name']]);
		//unset($arr[self::$field_platform_sales['name']]);
		//unset($arr[self::$field_platform_sales_id['name']]);
		//unset($arr[self::$field_customer['name']]);
		//unset($arr[self::$field_customer_id['name']]);
		//unset($arr[self::$field_headmaster['name']]);
		//unset($arr[self::$field_headmaster_id['name']]);
		//unset($arr[self::$field_payment_method['name']]);
		//unset($arr[self::$field_share_performance['name']]);
		//unset($arr[self::$field_remark['name']]);
		return $arr;
	}

}

p_generationoperation_soft::init_schema();
