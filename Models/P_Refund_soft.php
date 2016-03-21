<?php

/**
* 软件退款表
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_refund_soft.php'] = 1;

use Models\Base\Model;

class p_refund_soft extends Model{

	public static $field_id;
	public static $field_s_id;
	public static $field_money;
	public static $field_recordMoney;
	public static $field_retrieve;
	public static $field_refund_rate;
	public static $field_ind_refund_rate;
	public static $field_totalmoney;
	public static $field_date;
	public static $field_refund_type;
	public static $field_status;
	public static $field_add_user;
	public static $field_duty;
	public static $field_reason;
	public static $field_name;
	public static $field_ww;
	public static $field_presale;
	public static $field_presale_id;
	public static $field_customer;
	public static $field_customer_id;
	public static $field_controlman;
	public static $field_cd_alipay;
	public static $field_refund_shop;
	public static $field_setmeal;
	public static $field_is_logoff_dbb;
	public static $field_remark;
	public static $field_add_timt;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_s_id = Model::define_field('s_id', 'int', 0);
		self::$field_money = Model::define_field('money', 'float', 0.00);
		self::$field_recordMoney = Model::define_field('recordMoney', 'float', 0.00);
		self::$field_retrieve = Model::define_field('retrieve', 'float', 0.00);
		self::$field_refund_rate = Model::define_field('refund_rate', 'float', 0.00);
		self::$field_ind_refund_rate = Model::define_field('ind_refund_rate', 'float', 0.00);
		self::$field_totalmoney = Model::define_field('totalmoney', 'float', 0.00);
		self::$field_date = Model::define_field('date', 'date', NULL);
		self::$field_refund_type = Model::define_field('refund_type', 'string', NULL);
		self::$field_status = Model::define_field('status', 'string', NULL);
		self::$field_add_user = Model::define_field('add_user', 'string', NULL);
		self::$field_duty = Model::define_field('duty', 'string', NULL);
		self::$field_reason = Model::define_field('reason', 'string', NULL);
		self::$field_name = Model::define_field('name', 'string', NULL);
		self::$field_ww = Model::define_field('ww', 'string', NULL);
		self::$field_presale = Model::define_field('presale', 'string', NULL);
		self::$field_presale_id = Model::define_field('presale_id', 'int', 0);
		self::$field_customer = Model::define_field('customer', 'string', NULL);
		self::$field_customer_id = Model::define_field('customer_id', 'int', 0);
		self::$field_controlman = Model::define_field('controlman', 'string', NULL);
		self::$field_cd_alipay = Model::define_field('cd_alipay', 'string', NULL);
		self::$field_refund_shop = Model::define_field('refund_shop', 'string', NULL);
		self::$field_setmeal = Model::define_field('setmeal', 'string', NULL);
		self::$field_is_logoff_dbb = Model::define_field('is_logoff_dbb', 'string', NULL);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$field_add_timt = Model::define_field('add_timt', 'datetime', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('p_refund_soft', array(
			self::$field_id,
			self::$field_s_id,
			self::$field_money,
			self::$field_recordMoney,
			self::$field_retrieve,
			self::$field_refund_rate,
			self::$field_ind_refund_rate,
			self::$field_totalmoney,
			self::$field_date,
			self::$field_refund_type,
			self::$field_status,
			self::$field_add_user,
			self::$field_duty,
			self::$field_reason,
			self::$field_name,
			self::$field_ww,
			self::$field_presale,
			self::$field_presale_id,
			self::$field_customer,
			self::$field_customer_id,
			self::$field_controlman,
			self::$field_cd_alipay,
			self::$field_refund_shop,
			self::$field_setmeal,
			self::$field_is_logoff_dbb,
			self::$field_remark,
			self::$field_add_timt
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

	public function get_money() {
		return $this->get_field_value(self::$field_money);
	}

	public function set_money($money) {
		$this->set_field_value(self::$field_money, $money);
	}

	public function get_recordMoney() {
		return $this->get_field_value(self::$field_recordMoney);
	}

	public function set_recordMoney($recordMoney) {
		$this->set_field_value(self::$field_recordMoney, $recordMoney);
	}

	public function get_retrieve() {
		return $this->get_field_value(self::$field_retrieve);
	}

	public function set_retrieve($retrieve) {
		$this->set_field_value(self::$field_retrieve, $retrieve);
	}

	public function get_refund_rate() {
		return $this->get_field_value(self::$field_refund_rate);
	}

	public function set_refund_rate($refund_rate) {
		$this->set_field_value(self::$field_refund_rate, $refund_rate);
	}

	public function get_ind_refund_rate() {
		return $this->get_field_value(self::$field_ind_refund_rate);
	}

	public function set_ind_refund_rate($ind_refund_rate) {
		$this->set_field_value(self::$field_ind_refund_rate, $ind_refund_rate);
	}

	public function get_totalmoney() {
		return $this->get_field_value(self::$field_totalmoney);
	}

	public function set_totalmoney($totalmoney) {
		$this->set_field_value(self::$field_totalmoney, $totalmoney);
	}

	public function get_date() {
		return $this->get_field_value(self::$field_date);
	}

	public function set_date($date) {
		$this->set_field_value(self::$field_date, $date);
	}

	public function get_refund_type() {
		return $this->get_field_value(self::$field_refund_type);
	}

	public function set_refund_type($refund_type) {
		$this->set_field_value(self::$field_refund_type, $refund_type);
	}

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function get_add_user() {
		return $this->get_field_value(self::$field_add_user);
	}

	public function set_add_user($add_user) {
		$this->set_field_value(self::$field_add_user, $add_user);
	}

	public function get_duty() {
		return $this->get_field_value(self::$field_duty);
	}

	public function set_duty($duty) {
		$this->set_field_value(self::$field_duty, $duty);
	}

	public function get_reason() {
		return $this->get_field_value(self::$field_reason);
	}

	public function set_reason($reason) {
		$this->set_field_value(self::$field_reason, $reason);
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

	public function get_presale() {
		return $this->get_field_value(self::$field_presale);
	}

	public function set_presale($presale) {
		$this->set_field_value(self::$field_presale, $presale);
	}

	public function get_presale_id() {
		return $this->get_field_value(self::$field_presale_id);
	}

	public function set_presale_id($presale_id) {
		$this->set_field_value(self::$field_presale_id, $presale_id);
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

	public function get_controlman() {
		return $this->get_field_value(self::$field_controlman);
	}

	public function set_controlman($controlman) {
		$this->set_field_value(self::$field_controlman, $controlman);
	}

	public function get_cd_alipay() {
		return $this->get_field_value(self::$field_cd_alipay);
	}

	public function set_cd_alipay($cd_alipay) {
		$this->set_field_value(self::$field_cd_alipay, $cd_alipay);
	}

	public function get_refund_shop() {
		return $this->get_field_value(self::$field_refund_shop);
	}

	public function set_refund_shop($refund_shop) {
		$this->set_field_value(self::$field_refund_shop, $refund_shop);
	}

	public function get_setmeal() {
		return $this->get_field_value(self::$field_setmeal);
	}

	public function set_setmeal($setmeal) {
		$this->set_field_value(self::$field_setmeal, $setmeal);
	}

	public function get_is_logoff_dbb() {
		return $this->get_field_value(self::$field_is_logoff_dbb);
	}

	public function set_is_logoff_dbb($is_logoff_dbb) {
		$this->set_field_value(self::$field_is_logoff_dbb, $is_logoff_dbb);
	}

	public function get_remark() {
		return $this->get_field_value(self::$field_remark);
	}

	public function set_remark($remark) {
		$this->set_field_value(self::$field_remark, $remark);
	}

	public function get_add_timt() {
		return $this->get_field_value(self::$field_add_timt);
	}

	public function set_add_timt($add_timt) {
		$this->set_field_value(self::$field_add_timt, $add_timt);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_s_id['name']]);
		//unset($arr[self::$field_money['name']]);
		//unset($arr[self::$field_recordMoney['name']]);
		//unset($arr[self::$field_retrieve['name']]);
		//unset($arr[self::$field_refund_rate['name']]);
		//unset($arr[self::$field_ind_refund_rate['name']]);
		//unset($arr[self::$field_totalmoney['name']]);
		//unset($arr[self::$field_date['name']]);
		//unset($arr[self::$field_refund_type['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_add_user['name']]);
		//unset($arr[self::$field_duty['name']]);
		//unset($arr[self::$field_reason['name']]);
		//unset($arr[self::$field_name['name']]);
		//unset($arr[self::$field_ww['name']]);
		//unset($arr[self::$field_presale['name']]);
		//unset($arr[self::$field_presale_id['name']]);
		//unset($arr[self::$field_customer['name']]);
		//unset($arr[self::$field_customer_id['name']]);
		//unset($arr[self::$field_controlman['name']]);
		//unset($arr[self::$field_cd_alipay['name']]);
		//unset($arr[self::$field_refund_shop['name']]);
		//unset($arr[self::$field_setmeal['name']]);
		//unset($arr[self::$field_is_logoff_dbb['name']]);
		//unset($arr[self::$field_remark['name']]);
		//unset($arr[self::$field_add_timt['name']]);
		return $arr;
	}

}

p_refund_soft::init_schema();
