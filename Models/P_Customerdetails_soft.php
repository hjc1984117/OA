<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_customerdetails_soft.php'] = 1;

use Models\Base\Model;

class p_customerdetails_soft extends Model{

	public static $field_id;
	public static $field_sale_id;
	public static $field_presales_id;
	public static $field_presales;
	public static $field_customer;
	public static $field_customer_id;
	public static $field_date;
	public static $field_ww;
	public static $field_qq;
	public static $field_edit_qq;
	public static $field_money;
	public static $field_payment;
	public static $field_is_receive;
	public static $field_is_reviews;
	public static $field_is_addto_reviews;
	public static $field_is_add_qq;
	public static $field_is_added;
	public static $field_is_refund;
	public static $field_is_two_sales;
	public static $field_remark;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_sale_id = Model::define_field('sale_id', 'int', 0);
		self::$field_presales_id = Model::define_field('presales_id', 'int', 0);
		self::$field_presales = Model::define_field('presales', 'string', NULL);
		self::$field_customer = Model::define_field('customer', 'string', NULL);
		self::$field_customer_id = Model::define_field('customer_id', 'int', 0);
		self::$field_date = Model::define_field('date', 'date', NULL);
		self::$field_ww = Model::define_field('ww', 'string', NULL);
		self::$field_qq = Model::define_field('qq', 'string', NULL);
		self::$field_edit_qq = Model::define_field('edit_qq', 'int', 0);
		self::$field_money = Model::define_field('money', 'string', NULL);
		self::$field_payment = Model::define_field('payment', 'string', NULL);
		self::$field_is_receive = Model::define_field('is_receive', 'int', 0);
		self::$field_is_reviews = Model::define_field('is_reviews', 'int', 0);
		self::$field_is_addto_reviews = Model::define_field('is_addto_reviews', 'int', 0);
		self::$field_is_add_qq = Model::define_field('is_add_qq', 'int', 0);
		self::$field_is_added = Model::define_field('is_added', 'int', 0);
		self::$field_is_refund = Model::define_field('is_refund', 'int', 0);
		self::$field_is_two_sales = Model::define_field('is_two_sales', 'int', 0);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('p_customerdetails_soft', array(
			self::$field_id,
			self::$field_sale_id,
			self::$field_presales_id,
			self::$field_presales,
			self::$field_customer,
			self::$field_customer_id,
			self::$field_date,
			self::$field_ww,
			self::$field_qq,
			self::$field_edit_qq,
			self::$field_money,
			self::$field_payment,
			self::$field_is_receive,
			self::$field_is_reviews,
			self::$field_is_addto_reviews,
			self::$field_is_add_qq,
			self::$field_is_added,
			self::$field_is_refund,
			self::$field_is_two_sales,
			self::$field_remark
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_sale_id() {
		return $this->get_field_value(self::$field_sale_id);
	}

	public function set_sale_id($sale_id) {
		$this->set_field_value(self::$field_sale_id, $sale_id);
	}

	public function get_presales_id() {
		return $this->get_field_value(self::$field_presales_id);
	}

	public function set_presales_id($presales_id) {
		$this->set_field_value(self::$field_presales_id, $presales_id);
	}

	public function get_presales() {
		return $this->get_field_value(self::$field_presales);
	}

	public function set_presales($presales) {
		$this->set_field_value(self::$field_presales, $presales);
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

	public function get_date() {
		return $this->get_field_value(self::$field_date);
	}

	public function set_date($date) {
		$this->set_field_value(self::$field_date, $date);
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

	public function get_edit_qq() {
		return $this->get_field_value(self::$field_edit_qq);
	}

	public function set_edit_qq($edit_qq) {
		$this->set_field_value(self::$field_edit_qq, $edit_qq);
	}

	public function get_money() {
		return $this->get_field_value(self::$field_money);
	}

	public function set_money($money) {
		$this->set_field_value(self::$field_money, $money);
	}

	public function get_payment() {
		return $this->get_field_value(self::$field_payment);
	}

	public function set_payment($payment) {
		$this->set_field_value(self::$field_payment, $payment);
	}

	public function get_is_receive() {
		return $this->get_field_value(self::$field_is_receive);
	}

	public function set_is_receive($is_receive) {
		$this->set_field_value(self::$field_is_receive, $is_receive);
	}

	public function get_is_reviews() {
		return $this->get_field_value(self::$field_is_reviews);
	}

	public function set_is_reviews($is_reviews) {
		$this->set_field_value(self::$field_is_reviews, $is_reviews);
	}

	public function get_is_addto_reviews() {
		return $this->get_field_value(self::$field_is_addto_reviews);
	}

	public function set_is_addto_reviews($is_addto_reviews) {
		$this->set_field_value(self::$field_is_addto_reviews, $is_addto_reviews);
	}

	public function get_is_add_qq() {
		return $this->get_field_value(self::$field_is_add_qq);
	}

	public function set_is_add_qq($is_add_qq) {
		$this->set_field_value(self::$field_is_add_qq, $is_add_qq);
	}

	public function get_is_added() {
		return $this->get_field_value(self::$field_is_added);
	}

	public function set_is_added($is_added) {
		$this->set_field_value(self::$field_is_added, $is_added);
	}

	public function get_is_refund() {
		return $this->get_field_value(self::$field_is_refund);
	}

	public function set_is_refund($is_refund) {
		$this->set_field_value(self::$field_is_refund, $is_refund);
	}

	public function get_is_two_sales() {
		return $this->get_field_value(self::$field_is_two_sales);
	}

	public function set_is_two_sales($is_two_sales) {
		$this->set_field_value(self::$field_is_two_sales, $is_two_sales);
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
		//unset($arr[self::$field_sale_id['name']]);
		//unset($arr[self::$field_presales_id['name']]);
		//unset($arr[self::$field_presales['name']]);
		//unset($arr[self::$field_customer['name']]);
		//unset($arr[self::$field_customer_id['name']]);
		//unset($arr[self::$field_date['name']]);
		//unset($arr[self::$field_ww['name']]);
		//unset($arr[self::$field_qq['name']]);
		//unset($arr[self::$field_edit_qq['name']]);
		//unset($arr[self::$field_money['name']]);
		//unset($arr[self::$field_payment['name']]);
		//unset($arr[self::$field_is_receive['name']]);
		//unset($arr[self::$field_is_reviews['name']]);
		//unset($arr[self::$field_is_addto_reviews['name']]);
		//unset($arr[self::$field_is_add_qq['name']]);
		//unset($arr[self::$field_is_added['name']]);
		//unset($arr[self::$field_is_refund['name']]);
		//unset($arr[self::$field_is_two_sales['name']]);
		//unset($arr[self::$field_remark['name']]);
		return $arr;
	}

}

p_customerdetails_soft::init_schema();
