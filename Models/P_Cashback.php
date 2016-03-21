<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_cashback.php'] = 1;

use Models\Base\Model;

class p_cashback extends Model{

	public static $field_id;
	public static $field_s_id;
	public static $field_duty;
	public static $field_cashback;
	public static $field_cashback_reason;
	public static $field_presale;
	public static $field_customer;
	public static $field_date;
	public static $field_name;
	public static $field_buydate;
	public static $field_ww;
	public static $field_channel;
	public static $field_money;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_s_id = Model::define_field('s_id', 'int', 0);
		self::$field_duty = Model::define_field('duty', 'string', NULL);
		self::$field_cashback = Model::define_field('cashback', 'float', 0.00);
		self::$field_cashback_reason = Model::define_field('cashback_reason', 'string', NULL);
		self::$field_presale = Model::define_field('presale', 'string', NULL);
		self::$field_customer = Model::define_field('customer', 'string', NULL);
		self::$field_date = Model::define_field('date', 'datetime', NULL);
		self::$field_name = Model::define_field('name', 'string', NULL);
		self::$field_buydate = Model::define_field('buydate', 'datetime', NULL);
		self::$field_ww = Model::define_field('ww', 'string', NULL);
		self::$field_channel = Model::define_field('channel', 'string', NULL);
		self::$field_money = Model::define_field('money', 'float', 0.00);
		self::$MODEL_SCHEMA = Model::build_schema('p_cashback', array(
			self::$field_id,
			self::$field_s_id,
			self::$field_duty,
			self::$field_cashback,
			self::$field_cashback_reason,
			self::$field_presale,
			self::$field_customer,
			self::$field_date,
			self::$field_name,
			self::$field_buydate,
			self::$field_ww,
			self::$field_channel,
			self::$field_money
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_s_id() {
		return $this->get_field_value(self::$field_s_id);
	}

	public function set_s_id($s_id) {
		$this->set_field_value(self::$field_s_id, $s_id);
	}

	public function get_duty() {
		return $this->get_field_value(self::$field_duty);
	}

	public function set_duty($duty) {
		$this->set_field_value(self::$field_duty, $duty);
	}

	public function get_cashback() {
		return $this->get_field_value(self::$field_cashback);
	}

	public function set_cashback($cashback) {
		$this->set_field_value(self::$field_cashback, $cashback);
	}

	public function get_cashback_reason() {
		return $this->get_field_value(self::$field_cashback_reason);
	}

	public function set_cashback_reason($cashback_reason) {
		$this->set_field_value(self::$field_cashback_reason, $cashback_reason);
	}

	public function get_presale() {
		return $this->get_field_value(self::$field_presale);
	}

	public function set_presale($presale) {
		$this->set_field_value(self::$field_presale, $presale);
	}

	public function get_customer() {
		return $this->get_field_value(self::$field_customer);
	}

	public function set_customer($customer) {
		$this->set_field_value(self::$field_customer, $customer);
	}

	public function get_date() {
		return $this->get_field_value(self::$field_date);
	}

	public function set_date($date) {
		$this->set_field_value(self::$field_date, $date);
	}

	public function get_name() {
		return $this->get_field_value(self::$field_name);
	}

	public function set_name($name) {
		$this->set_field_value(self::$field_name, $name);
	}

	public function get_buydate() {
		return $this->get_field_value(self::$field_buydate);
	}

	public function set_buydate($buydate) {
		$this->set_field_value(self::$field_buydate, $buydate);
	}

	public function get_ww() {
		return $this->get_field_value(self::$field_ww);
	}

	public function set_ww($ww) {
		$this->set_field_value(self::$field_ww, $ww);
	}

	public function get_channel() {
		return $this->get_field_value(self::$field_channel);
	}

	public function set_channel($channel) {
		$this->set_field_value(self::$field_channel, $channel);
	}

	public function get_money() {
		return $this->get_field_value(self::$field_money);
	}

	public function set_money($money) {
		$this->set_field_value(self::$field_money, $money);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_s_id['name']]);
		//unset($arr[self::$field_duty['name']]);
		//unset($arr[self::$field_cashback['name']]);
		//unset($arr[self::$field_cashback_reason['name']]);
		//unset($arr[self::$field_presale['name']]);
		//unset($arr[self::$field_customer['name']]);
		//unset($arr[self::$field_date['name']]);
		//unset($arr[self::$field_name['name']]);
		//unset($arr[self::$field_buydate['name']]);
		//unset($arr[self::$field_ww['name']]);
		//unset($arr[self::$field_channel['name']]);
		//unset($arr[self::$field_money['name']]);
		return $arr;
	}

}

p_cashback::init_schema();
