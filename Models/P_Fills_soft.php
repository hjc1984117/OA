<?php

/**
* 补欠款
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_fills_soft.php'] = 1;

use Models\Base\Model;

class p_fills_soft extends Model{

	public static $field_id;
	public static $field_name;
	public static $field_ww;
	public static $field_mobile;
	public static $field_fill_sum;
	public static $field_add_time;
	public static $field_add_name;
	public static $field_payment;
	public static $field_channel;
	public static $field_attachment;
	public static $field_customer;
	public static $field_customer_id;
	public static $field_nick_name;
	public static $field_remark;
	public static $field_sale_id;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_name = Model::define_field('name', 'string', NULL);
		self::$field_ww = Model::define_field('ww', 'string', NULL);
		self::$field_mobile = Model::define_field('mobile', 'string', NULL);
		self::$field_fill_sum = Model::define_field('fill_sum', 'float', 0.00);
		self::$field_add_time = Model::define_field('add_time', 'datetime', NULL);
		self::$field_add_name = Model::define_field('add_name', 'string', NULL);
		self::$field_payment = Model::define_field('payment', 'string', NULL);
		self::$field_channel = Model::define_field('channel', 'string', NULL);
		self::$field_attachment = Model::define_field('attachment', 'string', NULL);
		self::$field_customer = Model::define_field('customer', 'string', NULL);
		self::$field_customer_id = Model::define_field('customer_id', 'int', 0);
		self::$field_nick_name = Model::define_field('nick_name', 'string', NULL);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$field_sale_id = Model::define_field('sale_id', 'int', 0);
		self::$MODEL_SCHEMA = Model::build_schema('p_fills_soft', array(
			self::$field_id,
			self::$field_name,
			self::$field_ww,
			self::$field_mobile,
			self::$field_fill_sum,
			self::$field_add_time,
			self::$field_add_name,
			self::$field_payment,
			self::$field_channel,
			self::$field_attachment,
			self::$field_customer,
			self::$field_customer_id,
			self::$field_nick_name,
			self::$field_remark,
			self::$field_sale_id
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_name() {
		return $this->get_field_value(self::$field_name);
	}

	public function set_name($name) {
		$this->set_field_value(self::$field_name, $name);
	}

	public function get_ww() {
		return $this->get_field_value(self::$field_ww);
	}

	public function set_ww($ww) {
		$this->set_field_value(self::$field_ww, $ww);
	}

	public function get_mobile() {
		return $this->get_field_value(self::$field_mobile);
	}

	public function set_mobile($mobile) {
		$this->set_field_value(self::$field_mobile, $mobile);
	}

	public function get_fill_sum() {
		return $this->get_field_value(self::$field_fill_sum);
	}

	public function set_fill_sum($fill_sum) {
		$this->set_field_value(self::$field_fill_sum, $fill_sum);
	}

	public function get_add_time() {
		return $this->get_field_value(self::$field_add_time);
	}

	public function set_add_time($add_time) {
		$this->set_field_value(self::$field_add_time, $add_time);
	}

	public function get_add_name() {
		return $this->get_field_value(self::$field_add_name);
	}

	public function set_add_name($add_name) {
		$this->set_field_value(self::$field_add_name, $add_name);
	}

	public function get_payment() {
		return $this->get_field_value(self::$field_payment);
	}

	public function set_payment($payment) {
		$this->set_field_value(self::$field_payment, $payment);
	}

	public function get_channel() {
		return $this->get_field_value(self::$field_channel);
	}

	public function set_channel($channel) {
		$this->set_field_value(self::$field_channel, $channel);
	}

	public function get_attachment() {
		return $this->get_field_value(self::$field_attachment);
	}

	public function set_attachment($attachment) {
		$this->set_field_value(self::$field_attachment, $attachment);
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

	public function get_nick_name() {
		return $this->get_field_value(self::$field_nick_name);
	}

	public function set_nick_name($nick_name) {
		$this->set_field_value(self::$field_nick_name, $nick_name);
	}

	public function get_remark() {
		return $this->get_field_value(self::$field_remark);
	}

	public function set_remark($remark) {
		$this->set_field_value(self::$field_remark, $remark);
	}

	public function get_sale_id() {
		return $this->get_field_value(self::$field_sale_id);
	}

	public function set_sale_id($sale_id) {
		$this->set_field_value(self::$field_sale_id, $sale_id);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_name['name']]);
		//unset($arr[self::$field_ww['name']]);
		//unset($arr[self::$field_mobile['name']]);
		//unset($arr[self::$field_fill_sum['name']]);
		//unset($arr[self::$field_add_time['name']]);
		//unset($arr[self::$field_add_name['name']]);
		//unset($arr[self::$field_payment['name']]);
		//unset($arr[self::$field_channel['name']]);
		//unset($arr[self::$field_attachment['name']]);
		//unset($arr[self::$field_customer['name']]);
		//unset($arr[self::$field_customer_id['name']]);
		//unset($arr[self::$field_nick_name['name']]);
		//unset($arr[self::$field_remark['name']]);
		//unset($arr[self::$field_sale_id['name']]);
		return $arr;
	}

}

p_fills_soft::init_schema();
