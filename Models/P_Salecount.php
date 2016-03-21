<?php

/**
* 销售统计
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_salecount.php'] = 1;

use Models\Base\Model;

class p_salecount extends Model{

	public static $field_id;
	public static $field_addtime;
	public static $field_ww;
	public static $field_name;
	public static $field_qq;
	public static $field_mobile;
	public static $field_money;
	public static $field_arrears;
	public static $field_setmeal;
	public static $field_payment;
	public static $field_channel;
	public static $field_presales;
	public static $field_presales_id;
	public static $field_customer;
	public static $field_customer_id;
	public static $field_nick_name;
	public static $field_province;
	public static $field_address;
	public static $field_remark;
	public static $field_group_id;
	public static $field_status;
	public static $field_isTimely;
	public static $field_isQQTeach;
	public static $field_isTmallTeach_qj;
	public static $field_isTmallTeach_zy;
	public static $field_scheduledPackage;
	public static $field_attachment;
	public static $field_conflictWith;
	public static $field_customer2;
	public static $field_customer_id2;
	public static $field_nick_name2;
	public static $field_praise;
	public static $field_customer_explain;
	public static $field_customer_date;
	public static $field_presales_cashback;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_addtime = Model::define_field('addtime', 'datetime', NULL);
		self::$field_ww = Model::define_field('ww', 'string', NULL);
		self::$field_name = Model::define_field('name', 'string', NULL);
		self::$field_qq = Model::define_field('qq', 'string', NULL);
		self::$field_mobile = Model::define_field('mobile', 'string', NULL);
		self::$field_money = Model::define_field('money', 'float', 0.00);
		self::$field_arrears = Model::define_field('arrears', 'float', 0.00);
		self::$field_setmeal = Model::define_field('setmeal', 'string', NULL);
		self::$field_payment = Model::define_field('payment', 'string', NULL);
		self::$field_channel = Model::define_field('channel', 'string', NULL);
		self::$field_presales = Model::define_field('presales', 'string', NULL);
		self::$field_presales_id = Model::define_field('presales_id', 'int', 0);
		self::$field_customer = Model::define_field('customer', 'string', NULL);
		self::$field_customer_id = Model::define_field('customer_id', 'int', 0);
		self::$field_nick_name = Model::define_field('nick_name', 'string', NULL);
		self::$field_province = Model::define_field('province', 'string', NULL);
		self::$field_address = Model::define_field('address', 'string', NULL);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$field_group_id = Model::define_field('group_id', 'int', 0);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$field_isTimely = Model::define_field('isTimely', 'int', 0);
		self::$field_isQQTeach = Model::define_field('isQQTeach', 'int', 0);
		self::$field_isTmallTeach_qj = Model::define_field('isTmallTeach_qj', 'int', 0);
		self::$field_isTmallTeach_zy = Model::define_field('isTmallTeach_zy', 'int', 0);
		self::$field_scheduledPackage = Model::define_field('scheduledPackage', 'int', 0);
		self::$field_attachment = Model::define_field('attachment', 'string', NULL);
		self::$field_conflictWith = Model::define_field('conflictWith', 'int', 0);
		self::$field_customer2 = Model::define_field('customer2', 'string', NULL);
		self::$field_customer_id2 = Model::define_field('customer_id2', 'int', 0);
		self::$field_nick_name2 = Model::define_field('nick_name2', 'string', NULL);
		self::$field_praise = Model::define_field('praise', 'int', 0);
		self::$field_customer_explain = Model::define_field('customer_explain', 'string', NULL);
		self::$field_customer_date = Model::define_field('customer_date', 'datetime', NULL);
		self::$field_presales_cashback = Model::define_field('presales_cashback', 'float', 0.00);
		self::$MODEL_SCHEMA = Model::build_schema('p_salecount', array(
			self::$field_id,
			self::$field_addtime,
			self::$field_ww,
			self::$field_name,
			self::$field_qq,
			self::$field_mobile,
			self::$field_money,
			self::$field_arrears,
			self::$field_setmeal,
			self::$field_payment,
			self::$field_channel,
			self::$field_presales,
			self::$field_presales_id,
			self::$field_customer,
			self::$field_customer_id,
			self::$field_nick_name,
			self::$field_province,
			self::$field_address,
			self::$field_remark,
			self::$field_group_id,
			self::$field_status,
			self::$field_isTimely,
			self::$field_isQQTeach,
			self::$field_isTmallTeach_qj,
			self::$field_isTmallTeach_zy,
			self::$field_scheduledPackage,
			self::$field_attachment,
			self::$field_conflictWith,
			self::$field_customer2,
			self::$field_customer_id2,
			self::$field_nick_name2,
			self::$field_praise,
			self::$field_customer_explain,
			self::$field_customer_date,
			self::$field_presales_cashback
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

	public function get_ww() {
		return $this->get_field_value(self::$field_ww);
	}

	public function set_ww($ww) {
		$this->set_field_value(self::$field_ww, $ww);
	}

	public function get_name() {
		return $this->get_field_value(self::$field_name);
	}

	public function set_name($name) {
		$this->set_field_value(self::$field_name, $name);
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

	public function get_arrears() {
		return $this->get_field_value(self::$field_arrears);
	}

	public function set_arrears($arrears) {
		$this->set_field_value(self::$field_arrears, $arrears);
	}

	public function get_setmeal() {
		return $this->get_field_value(self::$field_setmeal);
	}

	public function set_setmeal($setmeal) {
		$this->set_field_value(self::$field_setmeal, $setmeal);
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

	public function get_presales() {
		return $this->get_field_value(self::$field_presales);
	}

	public function set_presales($presales) {
		$this->set_field_value(self::$field_presales, $presales);
	}

	public function get_presales_id() {
		return $this->get_field_value(self::$field_presales_id);
	}

	public function set_presales_id($presales_id) {
		$this->set_field_value(self::$field_presales_id, $presales_id);
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

	public function get_province() {
		return $this->get_field_value(self::$field_province);
	}

	public function set_province($province) {
		$this->set_field_value(self::$field_province, $province);
	}

	public function get_address() {
		return $this->get_field_value(self::$field_address);
	}

	public function set_address($address) {
		$this->set_field_value(self::$field_address, $address);
	}

	public function get_remark() {
		return $this->get_field_value(self::$field_remark);
	}

	public function set_remark($remark) {
		$this->set_field_value(self::$field_remark, $remark);
	}

	public function get_group_id() {
		return $this->get_field_value(self::$field_group_id);
	}

	public function set_group_id($group_id) {
		$this->set_field_value(self::$field_group_id, $group_id);
	}

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function get_isTimely() {
		return $this->get_field_value(self::$field_isTimely);
	}

	public function set_isTimely($isTimely) {
		$this->set_field_value(self::$field_isTimely, $isTimely);
	}

	public function get_isQQTeach() {
		return $this->get_field_value(self::$field_isQQTeach);
	}

	public function set_isQQTeach($isQQTeach) {
		$this->set_field_value(self::$field_isQQTeach, $isQQTeach);
	}

	public function get_isTmallTeach_qj() {
		return $this->get_field_value(self::$field_isTmallTeach_qj);
	}

	public function set_isTmallTeach_qj($isTmallTeach_qj) {
		$this->set_field_value(self::$field_isTmallTeach_qj, $isTmallTeach_qj);
	}

	public function get_isTmallTeach_zy() {
		return $this->get_field_value(self::$field_isTmallTeach_zy);
	}

	public function set_isTmallTeach_zy($isTmallTeach_zy) {
		$this->set_field_value(self::$field_isTmallTeach_zy, $isTmallTeach_zy);
	}

	public function get_scheduledPackage() {
		return $this->get_field_value(self::$field_scheduledPackage);
	}

	public function set_scheduledPackage($scheduledPackage) {
		$this->set_field_value(self::$field_scheduledPackage, $scheduledPackage);
	}

	public function get_attachment() {
		return $this->get_field_value(self::$field_attachment);
	}

	public function set_attachment($attachment) {
		$this->set_field_value(self::$field_attachment, $attachment);
	}

	public function get_conflictWith() {
		return $this->get_field_value(self::$field_conflictWith);
	}

	public function set_conflictWith($conflictWith) {
		$this->set_field_value(self::$field_conflictWith, $conflictWith);
	}

	public function get_customer2() {
		return $this->get_field_value(self::$field_customer2);
	}

	public function set_customer2($customer2) {
		$this->set_field_value(self::$field_customer2, $customer2);
	}

	public function get_customer_id2() {
		return $this->get_field_value(self::$field_customer_id2);
	}

	public function set_customer_id2($customer_id2) {
		$this->set_field_value(self::$field_customer_id2, $customer_id2);
	}

	public function get_nick_name2() {
		return $this->get_field_value(self::$field_nick_name2);
	}

	public function set_nick_name2($nick_name2) {
		$this->set_field_value(self::$field_nick_name2, $nick_name2);
	}

	public function get_praise() {
		return $this->get_field_value(self::$field_praise);
	}

	public function set_praise($praise) {
		$this->set_field_value(self::$field_praise, $praise);
	}

	public function get_customer_explain() {
		return $this->get_field_value(self::$field_customer_explain);
	}

	public function set_customer_explain($customer_explain) {
		$this->set_field_value(self::$field_customer_explain, $customer_explain);
	}

	public function get_customer_date() {
		return $this->get_field_value(self::$field_customer_date);
	}

	public function set_customer_date($customer_date) {
		$this->set_field_value(self::$field_customer_date, $customer_date);
	}

	public function get_presales_cashback() {
		return $this->get_field_value(self::$field_presales_cashback);
	}

	public function set_presales_cashback($presales_cashback) {
		$this->set_field_value(self::$field_presales_cashback, $presales_cashback);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_addtime['name']]);
		//unset($arr[self::$field_ww['name']]);
		//unset($arr[self::$field_name['name']]);
		//unset($arr[self::$field_qq['name']]);
		//unset($arr[self::$field_mobile['name']]);
		//unset($arr[self::$field_money['name']]);
		//unset($arr[self::$field_arrears['name']]);
		//unset($arr[self::$field_setmeal['name']]);
		//unset($arr[self::$field_payment['name']]);
		//unset($arr[self::$field_channel['name']]);
		//unset($arr[self::$field_presales['name']]);
		//unset($arr[self::$field_presales_id['name']]);
		//unset($arr[self::$field_customer['name']]);
		//unset($arr[self::$field_customer_id['name']]);
		//unset($arr[self::$field_nick_name['name']]);
		//unset($arr[self::$field_province['name']]);
		//unset($arr[self::$field_address['name']]);
		//unset($arr[self::$field_remark['name']]);
		//unset($arr[self::$field_group_id['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_isTimely['name']]);
		//unset($arr[self::$field_isQQTeach['name']]);
		//unset($arr[self::$field_isTmallTeach_qj['name']]);
		//unset($arr[self::$field_isTmallTeach_zy['name']]);
		//unset($arr[self::$field_scheduledPackage['name']]);
		//unset($arr[self::$field_attachment['name']]);
		//unset($arr[self::$field_conflictWith['name']]);
		//unset($arr[self::$field_customer2['name']]);
		//unset($arr[self::$field_customer_id2['name']]);
		//unset($arr[self::$field_nick_name2['name']]);
		//unset($arr[self::$field_praise['name']]);
		//unset($arr[self::$field_customer_explain['name']]);
		//unset($arr[self::$field_customer_date['name']]);
		//unset($arr[self::$field_presales_cashback['name']]);
		return $arr;
	}

}

p_salecount::init_schema();
