<?php

/**
* QQ接入
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_qqaccess.php'] = 1;

use Models\Base\Model;

class p_qqaccess extends Model{

	public static $field_id;
	public static $field_addtime;
	public static $field_add_userid;
	public static $field_add_username;
	public static $field_presales;
	public static $field_presales_id;
	public static $field_qq_num;
	public static $field_customer_num;
	public static $field_customer_address;
	public static $field_channel;
	public static $field_access_time;
	public static $field_keyword;
	public static $field_into_url;
	public static $field_visitor_info;
	public static $field_visitor_sour;
	public static $field_hasValidation;
	public static $field_validation;
	public static $field_qq_is_repetition;
	public static $field_wait_time;
	public static $field_handle_time;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_addtime = Model::define_field('addtime', 'datetime', NULL);
		self::$field_add_userid = Model::define_field('add_userid', 'int', 0);
		self::$field_add_username = Model::define_field('add_username', 'string', NULL);
		self::$field_presales = Model::define_field('presales', 'string', NULL);
		self::$field_presales_id = Model::define_field('presales_id', 'int', 0);
		self::$field_qq_num = Model::define_field('qq_num', 'string', NULL);
		self::$field_customer_num = Model::define_field('customer_num', 'string', NULL);
		self::$field_customer_address = Model::define_field('customer_address', 'string', NULL);
		self::$field_channel = Model::define_field('channel', 'string', NULL);
		self::$field_access_time = Model::define_field('access_time', 'datetime', NULL);
		self::$field_keyword = Model::define_field('keyword', 'string', NULL);
		self::$field_into_url = Model::define_field('into_url', 'string', NULL);
		self::$field_visitor_info = Model::define_field('visitor_info', 'string', NULL);
		self::$field_visitor_sour = Model::define_field('visitor_sour', 'string', NULL);
		self::$field_hasValidation = Model::define_field('hasValidation', 'int', 0);
		self::$field_validation = Model::define_field('validation', 'string', NULL);
		self::$field_qq_is_repetition = Model::define_field('qq_is_repetition', 'int', 0);
		self::$field_wait_time = Model::define_field('wait_time', 'int', 0);
		self::$field_handle_time = Model::define_field('handle_time', 'datetime', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('p_qqaccess', array(
			self::$field_id,
			self::$field_addtime,
			self::$field_add_userid,
			self::$field_add_username,
			self::$field_presales,
			self::$field_presales_id,
			self::$field_qq_num,
			self::$field_customer_num,
			self::$field_customer_address,
			self::$field_channel,
			self::$field_access_time,
			self::$field_keyword,
			self::$field_into_url,
			self::$field_visitor_info,
			self::$field_visitor_sour,
			self::$field_hasValidation,
			self::$field_validation,
			self::$field_qq_is_repetition,
			self::$field_wait_time,
			self::$field_handle_time
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

	public function get_add_userid() {
		return $this->get_field_value(self::$field_add_userid);
	}

	public function set_add_userid($add_userid) {
		$this->set_field_value(self::$field_add_userid, $add_userid);
	}

	public function get_add_username() {
		return $this->get_field_value(self::$field_add_username);
	}

	public function set_add_username($add_username) {
		$this->set_field_value(self::$field_add_username, $add_username);
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

	public function get_qq_num() {
		return $this->get_field_value(self::$field_qq_num);
	}

	public function set_qq_num($qq_num) {
		$this->set_field_value(self::$field_qq_num, $qq_num);
	}

	public function get_customer_num() {
		return $this->get_field_value(self::$field_customer_num);
	}

	public function set_customer_num($customer_num) {
		$this->set_field_value(self::$field_customer_num, $customer_num);
	}

	public function get_customer_address() {
		return $this->get_field_value(self::$field_customer_address);
	}

	public function set_customer_address($customer_address) {
		$this->set_field_value(self::$field_customer_address, $customer_address);
	}

	public function get_channel() {
		return $this->get_field_value(self::$field_channel);
	}

	public function set_channel($channel) {
		$this->set_field_value(self::$field_channel, $channel);
	}

	public function get_access_time() {
		return $this->get_field_value(self::$field_access_time);
	}

	public function set_access_time($access_time) {
		$this->set_field_value(self::$field_access_time, $access_time);
	}

	public function get_keyword() {
		return $this->get_field_value(self::$field_keyword);
	}

	public function set_keyword($keyword) {
		$this->set_field_value(self::$field_keyword, $keyword);
	}

	public function get_into_url() {
		return $this->get_field_value(self::$field_into_url);
	}

	public function set_into_url($into_url) {
		$this->set_field_value(self::$field_into_url, $into_url);
	}

	public function get_visitor_info() {
		return $this->get_field_value(self::$field_visitor_info);
	}

	public function set_visitor_info($visitor_info) {
		$this->set_field_value(self::$field_visitor_info, $visitor_info);
	}

	public function get_visitor_sour() {
		return $this->get_field_value(self::$field_visitor_sour);
	}

	public function set_visitor_sour($visitor_sour) {
		$this->set_field_value(self::$field_visitor_sour, $visitor_sour);
	}

	public function get_hasValidation() {
		return $this->get_field_value(self::$field_hasValidation);
	}

	public function set_hasValidation($hasValidation) {
		$this->set_field_value(self::$field_hasValidation, $hasValidation);
	}

	public function get_validation() {
		return $this->get_field_value(self::$field_validation);
	}

	public function set_validation($validation) {
		$this->set_field_value(self::$field_validation, $validation);
	}

	public function get_qq_is_repetition() {
		return $this->get_field_value(self::$field_qq_is_repetition);
	}

	public function set_qq_is_repetition($qq_is_repetition) {
		$this->set_field_value(self::$field_qq_is_repetition, $qq_is_repetition);
	}

	public function get_wait_time() {
		return $this->get_field_value(self::$field_wait_time);
	}

	public function set_wait_time($wait_time) {
		$this->set_field_value(self::$field_wait_time, $wait_time);
	}

	public function get_handle_time() {
		return $this->get_field_value(self::$field_handle_time);
	}

	public function set_handle_time($handle_time) {
		$this->set_field_value(self::$field_handle_time, $handle_time);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_addtime['name']]);
		//unset($arr[self::$field_add_userid['name']]);
		//unset($arr[self::$field_add_username['name']]);
		//unset($arr[self::$field_presales['name']]);
		//unset($arr[self::$field_presales_id['name']]);
		//unset($arr[self::$field_qq_num['name']]);
		//unset($arr[self::$field_customer_num['name']]);
		//unset($arr[self::$field_customer_address['name']]);
		//unset($arr[self::$field_channel['name']]);
		//unset($arr[self::$field_access_time['name']]);
		//unset($arr[self::$field_keyword['name']]);
		//unset($arr[self::$field_into_url['name']]);
		//unset($arr[self::$field_visitor_info['name']]);
		//unset($arr[self::$field_visitor_sour['name']]);
		//unset($arr[self::$field_hasValidation['name']]);
		//unset($arr[self::$field_validation['name']]);
		//unset($arr[self::$field_qq_is_repetition['name']]);
		//unset($arr[self::$field_wait_time['name']]);
		//unset($arr[self::$field_handle_time['name']]);
		return $arr;
	}

}

p_qqaccess::init_schema();
