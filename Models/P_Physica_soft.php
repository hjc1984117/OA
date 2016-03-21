<?php

/**
* 实物业绩
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_physica_soft.php'] = 1;

use Models\Base\Model;

class p_physica_soft extends Model{

	public static $field_id;
	public static $field_is_edit;
	public static $field_add_time;
	public static $field_ww;
	public static $field_qq;
	public static $field_name;
	public static $field_phone;
	public static $field_agent_category;
	public static $field_agent_price;
	public static $field_all_price;
	public static $field_free_price;
	public static $field_free_decoration;
	public static $field_isArrears;
	public static $field_isTeaching;
	public static $field_alipay_account;
	public static $field_payment_method;
	public static $field_customer;
	public static $field_customer_id;
	public static $field_rception;
	public static $field_rception_id;
	public static $field_isTe;
	public static $field_tra_num;
	public static $field_status;
	public static $field_p_arrears;
	public static $field_remark;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_is_edit = Model::define_field('is_edit', 'int', 0);
		self::$field_add_time = Model::define_field('add_time', 'datetime', NULL);
		self::$field_ww = Model::define_field('ww', 'string', NULL);
		self::$field_qq = Model::define_field('qq', 'string', NULL);
		self::$field_name = Model::define_field('name', 'string', NULL);
		self::$field_phone = Model::define_field('phone', 'string', NULL);
		self::$field_agent_category = Model::define_field('agent_category', 'string', NULL);
		self::$field_agent_price = Model::define_field('agent_price', 'float', 0.00);
		self::$field_all_price = Model::define_field('all_price', 'float', 0.00);
		self::$field_free_price = Model::define_field('free_price', 'float', 0.00);
		self::$field_free_decoration = Model::define_field('free_decoration', 'int', 0);
		self::$field_isArrears = Model::define_field('isArrears', 'int', 0);
		self::$field_isTeaching = Model::define_field('isTeaching', 'int', 0);
		self::$field_alipay_account = Model::define_field('alipay_account', 'string', NULL);
		self::$field_payment_method = Model::define_field('payment_method', 'string', NULL);
		self::$field_customer = Model::define_field('customer', 'string', NULL);
		self::$field_customer_id = Model::define_field('customer_id', 'int', 0);
		self::$field_rception = Model::define_field('rception', 'string', NULL);
		self::$field_rception_id = Model::define_field('rception_id', 'int', 0);
		self::$field_isTe = Model::define_field('isTe', 'int', 0);
		self::$field_tra_num = Model::define_field('tra_num', 'string', NULL);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$field_p_arrears = Model::define_field('p_arrears', 'float', 0.00);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('p_physica_soft', array(
			self::$field_id,
			self::$field_is_edit,
			self::$field_add_time,
			self::$field_ww,
			self::$field_qq,
			self::$field_name,
			self::$field_phone,
			self::$field_agent_category,
			self::$field_agent_price,
			self::$field_all_price,
			self::$field_free_price,
			self::$field_free_decoration,
			self::$field_isArrears,
			self::$field_isTeaching,
			self::$field_alipay_account,
			self::$field_payment_method,
			self::$field_customer,
			self::$field_customer_id,
			self::$field_rception,
			self::$field_rception_id,
			self::$field_isTe,
			self::$field_tra_num,
			self::$field_status,
			self::$field_p_arrears,
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

	public function get_agent_category() {
		return $this->get_field_value(self::$field_agent_category);
	}

	public function set_agent_category($agent_category) {
		$this->set_field_value(self::$field_agent_category, $agent_category);
	}

	public function get_agent_price() {
		return $this->get_field_value(self::$field_agent_price);
	}

	public function set_agent_price($agent_price) {
		$this->set_field_value(self::$field_agent_price, $agent_price);
	}

	public function get_all_price() {
		return $this->get_field_value(self::$field_all_price);
	}

	public function set_all_price($all_price) {
		$this->set_field_value(self::$field_all_price, $all_price);
	}

	public function get_free_price() {
		return $this->get_field_value(self::$field_free_price);
	}

	public function set_free_price($free_price) {
		$this->set_field_value(self::$field_free_price, $free_price);
	}

	public function get_free_decoration() {
		return $this->get_field_value(self::$field_free_decoration);
	}

	public function set_free_decoration($free_decoration) {
		$this->set_field_value(self::$field_free_decoration, $free_decoration);
	}

	public function get_isArrears() {
		return $this->get_field_value(self::$field_isArrears);
	}

	public function set_isArrears($isArrears) {
		$this->set_field_value(self::$field_isArrears, $isArrears);
	}

	public function get_isTeaching() {
		return $this->get_field_value(self::$field_isTeaching);
	}

	public function set_isTeaching($isTeaching) {
		$this->set_field_value(self::$field_isTeaching, $isTeaching);
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

	public function get_isTe() {
		return $this->get_field_value(self::$field_isTe);
	}

	public function set_isTe($isTe) {
		$this->set_field_value(self::$field_isTe, $isTe);
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
		//unset($arr[self::$field_ww['name']]);
		//unset($arr[self::$field_qq['name']]);
		//unset($arr[self::$field_name['name']]);
		//unset($arr[self::$field_phone['name']]);
		//unset($arr[self::$field_agent_category['name']]);
		//unset($arr[self::$field_agent_price['name']]);
		//unset($arr[self::$field_all_price['name']]);
		//unset($arr[self::$field_free_price['name']]);
		//unset($arr[self::$field_free_decoration['name']]);
		//unset($arr[self::$field_isArrears['name']]);
		//unset($arr[self::$field_isTeaching['name']]);
		//unset($arr[self::$field_alipay_account['name']]);
		//unset($arr[self::$field_payment_method['name']]);
		//unset($arr[self::$field_customer['name']]);
		//unset($arr[self::$field_customer_id['name']]);
		//unset($arr[self::$field_rception['name']]);
		//unset($arr[self::$field_rception_id['name']]);
		//unset($arr[self::$field_isTe['name']]);
		//unset($arr[self::$field_tra_num['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_p_arrears['name']]);
		//unset($arr[self::$field_remark['name']]);
		return $arr;
	}

}

p_physica_soft::init_schema();
