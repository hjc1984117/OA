<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_refundapply.php'] = 1;

use Models\Base\Model;

class p_refundapply extends Model{

	public static $field_id;
	public static $field_addtime;
	public static $field_apply_time;
	public static $field_sale_addtime;
	public static $field_end_time;
	public static $field_delay_time;
	public static $field_int_time;
	public static $field_name;
	public static $field_ww;
	public static $field_qq;
	public static $field_mobile;
	public static $field_money;
	public static $field_done_userid;
	public static $field_done_username;
	public static $field_sale_id;
	public static $field_payment;
	public static $field_customer;
	public static $field_customer_id;
	public static $field_nick_name;
	public static $field_rstatus;
	public static $field_arrears;
	public static $field_reason;
	public static $field_remark;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_addtime = Model::define_field('addtime', 'datetime', NULL);
		self::$field_apply_time = Model::define_field('apply_time', 'datetime', NULL);
		self::$field_sale_addtime = Model::define_field('sale_addtime', 'datetime', NULL);
		self::$field_end_time = Model::define_field('end_time', 'datetime', NULL);
		self::$field_delay_time = Model::define_field('delay_time', 'datetime', NULL);
		self::$field_int_time = Model::define_field('int_time', 'datetime', NULL);
		self::$field_name = Model::define_field('name', 'string', NULL);
		self::$field_ww = Model::define_field('ww', 'string', NULL);
		self::$field_qq = Model::define_field('qq', 'string', NULL);
		self::$field_mobile = Model::define_field('mobile', 'string', NULL);
		self::$field_money = Model::define_field('money', 'float', 0.00);
		self::$field_done_userid = Model::define_field('done_userid', 'int', 0);
		self::$field_done_username = Model::define_field('done_username', 'string', NULL);
		self::$field_sale_id = Model::define_field('sale_id', 'int', 0);
		self::$field_payment = Model::define_field('payment', 'string', NULL);
		self::$field_customer = Model::define_field('customer', 'string', NULL);
		self::$field_customer_id = Model::define_field('customer_id', 'int', 0);
		self::$field_nick_name = Model::define_field('nick_name', 'string', NULL);
		self::$field_rstatus = Model::define_field('rstatus', 'string', '1');
		self::$field_arrears = Model::define_field('arrears', 'float', 0.00);
		self::$field_reason = Model::define_field('reason', 'string', NULL);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('p_refundapply', array(
			self::$field_id,
			self::$field_addtime,
			self::$field_apply_time,
			self::$field_sale_addtime,
			self::$field_end_time,
			self::$field_delay_time,
			self::$field_int_time,
			self::$field_name,
			self::$field_ww,
			self::$field_qq,
			self::$field_mobile,
			self::$field_money,
			self::$field_done_userid,
			self::$field_done_username,
			self::$field_sale_id,
			self::$field_payment,
			self::$field_customer,
			self::$field_customer_id,
			self::$field_nick_name,
			self::$field_rstatus,
			self::$field_arrears,
			self::$field_reason,
			self::$field_remark
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

	public function get_apply_time() {
		return $this->get_field_value(self::$field_apply_time);
	}

	public function set_apply_time($apply_time) {
		$this->set_field_value(self::$field_apply_time, $apply_time);
	}

	public function get_sale_addtime() {
		return $this->get_field_value(self::$field_sale_addtime);
	}

	public function set_sale_addtime($sale_addtime) {
		$this->set_field_value(self::$field_sale_addtime, $sale_addtime);
	}

	public function get_end_time() {
		return $this->get_field_value(self::$field_end_time);
	}

	public function set_end_time($end_time) {
		$this->set_field_value(self::$field_end_time, $end_time);
	}

	public function get_delay_time() {
		return $this->get_field_value(self::$field_delay_time);
	}

	public function set_delay_time($delay_time) {
		$this->set_field_value(self::$field_delay_time, $delay_time);
	}

	public function get_int_time() {
		return $this->get_field_value(self::$field_int_time);
	}

	public function set_int_time($int_time) {
		$this->set_field_value(self::$field_int_time, $int_time);
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

	public function get_qq() {
		return $this->get_field_value(self::$field_qq);
	}

	public function set_qq($qq) {
		$this->set_field_value(self::$field_qq, $qq);
	}

	public function get_mobile() {
		return $this->get_field_value(self::$field_mobile);
	}

	public function set_mobile($mobile) {
		$this->set_field_value(self::$field_mobile, $mobile);
	}

	public function get_money() {
		return $this->get_field_value(self::$field_money);
	}

	public function set_money($money) {
		$this->set_field_value(self::$field_money, $money);
	}

	public function get_done_userid() {
		return $this->get_field_value(self::$field_done_userid);
	}

	public function set_done_userid($done_userid) {
		$this->set_field_value(self::$field_done_userid, $done_userid);
	}

	public function get_done_username() {
		return $this->get_field_value(self::$field_done_username);
	}

	public function set_done_username($done_username) {
		$this->set_field_value(self::$field_done_username, $done_username);
	}

	public function get_sale_id() {
		return $this->get_field_value(self::$field_sale_id);
	}

	public function set_sale_id($sale_id) {
		$this->set_field_value(self::$field_sale_id, $sale_id);
	}

	public function get_payment() {
		return $this->get_field_value(self::$field_payment);
	}

	public function set_payment($payment) {
		$this->set_field_value(self::$field_payment, $payment);
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

	public function get_rstatus() {
		return $this->get_field_value(self::$field_rstatus);
	}

	public function set_rstatus($rstatus) {
		$this->set_field_value(self::$field_rstatus, $rstatus);
	}

	public function get_arrears() {
		return $this->get_field_value(self::$field_arrears);
	}

	public function set_arrears($arrears) {
		$this->set_field_value(self::$field_arrears, $arrears);
	}

	public function get_reason() {
		return $this->get_field_value(self::$field_reason);
	}

	public function set_reason($reason) {
		$this->set_field_value(self::$field_reason, $reason);
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
		//unset($arr[self::$field_addtime['name']]);
		//unset($arr[self::$field_apply_time['name']]);
		//unset($arr[self::$field_sale_addtime['name']]);
		//unset($arr[self::$field_end_time['name']]);
		//unset($arr[self::$field_delay_time['name']]);
		//unset($arr[self::$field_int_time['name']]);
		//unset($arr[self::$field_name['name']]);
		//unset($arr[self::$field_ww['name']]);
		//unset($arr[self::$field_qq['name']]);
		//unset($arr[self::$field_mobile['name']]);
		//unset($arr[self::$field_money['name']]);
		//unset($arr[self::$field_done_userid['name']]);
		//unset($arr[self::$field_done_username['name']]);
		//unset($arr[self::$field_sale_id['name']]);
		//unset($arr[self::$field_payment['name']]);
		//unset($arr[self::$field_customer['name']]);
		//unset($arr[self::$field_customer_id['name']]);
		//unset($arr[self::$field_nick_name['name']]);
		//unset($arr[self::$field_rstatus['name']]);
		//unset($arr[self::$field_arrears['name']]);
		//unset($arr[self::$field_reason['name']]);
		//unset($arr[self::$field_remark['name']]);
		return $arr;
	}

}

p_refundapply::init_schema();
