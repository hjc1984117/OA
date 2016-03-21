<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_customerstatistics.php'] = 1;

use Models\Base\Model;

class p_customerstatistics extends Model{

	public static $field_id;
	public static $field_user_id;
	public static $field_user_name;
	public static $field_team;
	public static $field_group;
	public static $field_group_members;
	public static $field_category;
	public static $field_have_received;
	public static $field_receive_amount;
	public static $field_refund_num;
	public static $field_refund_amount;
	public static $field_change_Q;
	public static $field_flow_num;
	public static $field_physical_things;
	public static $field_decorate;
	public static $field_singular;
	public static $field_date;
	public static $field_suser_id;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_user_id = Model::define_field('user_id', 'int', 0);
		self::$field_user_name = Model::define_field('user_name', 'string', NULL);
		self::$field_team = Model::define_field('team', 'int', 0);
		self::$field_group = Model::define_field('group', 'int', 0);
		self::$field_group_members = Model::define_field('group_members', 'int', 0);
		self::$field_category = Model::define_field('category', 'string', 'C');
		self::$field_have_received = Model::define_field('have_received', 'int', 0);
		self::$field_receive_amount = Model::define_field('receive_amount', 'float', 0.00);
		self::$field_refund_num = Model::define_field('refund_num', 'float', 0.00);
		self::$field_refund_amount = Model::define_field('refund_amount', 'float', 0.00);
		self::$field_change_Q = Model::define_field('change_Q', 'int', 0);
		self::$field_flow_num = Model::define_field('flow_num', 'float', 0.00);
		self::$field_physical_things = Model::define_field('physical_things', 'float', 0.00);
		self::$field_decorate = Model::define_field('decorate', 'float', 0.00);
		self::$field_singular = Model::define_field('singular', 'int', 0);
		self::$field_date = Model::define_field('date', 'datetime', NULL);
		self::$field_suser_id = Model::define_field('suser_id', 'int', 0);
		self::$MODEL_SCHEMA = Model::build_schema('p_customerstatistics', array(
			self::$field_id,
			self::$field_user_id,
			self::$field_user_name,
			self::$field_team,
			self::$field_group,
			self::$field_group_members,
			self::$field_category,
			self::$field_have_received,
			self::$field_receive_amount,
			self::$field_refund_num,
			self::$field_refund_amount,
			self::$field_change_Q,
			self::$field_flow_num,
			self::$field_physical_things,
			self::$field_decorate,
			self::$field_singular,
			self::$field_date,
			self::$field_suser_id
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_user_id() {
		return $this->get_field_value(self::$field_user_id);
	}

	public function set_user_id($user_id) {
		$this->set_field_value(self::$field_user_id, $user_id);
	}

	public function get_user_name() {
		return $this->get_field_value(self::$field_user_name);
	}

	public function set_user_name($user_name) {
		$this->set_field_value(self::$field_user_name, $user_name);
	}

	public function get_team() {
		return $this->get_field_value(self::$field_team);
	}

	public function set_team($team) {
		$this->set_field_value(self::$field_team, $team);
	}

	public function get_group() {
		return $this->get_field_value(self::$field_group);
	}

	public function set_group($group) {
		$this->set_field_value(self::$field_group, $group);
	}

	public function get_group_members() {
		return $this->get_field_value(self::$field_group_members);
	}

	public function set_group_members($group_members) {
		$this->set_field_value(self::$field_group_members, $group_members);
	}

	public function get_category() {
		return $this->get_field_value(self::$field_category);
	}

	public function set_category($category) {
		$this->set_field_value(self::$field_category, $category);
	}

	public function get_have_received() {
		return $this->get_field_value(self::$field_have_received);
	}

	public function set_have_received($have_received) {
		$this->set_field_value(self::$field_have_received, $have_received);
	}

	public function get_receive_amount() {
		return $this->get_field_value(self::$field_receive_amount);
	}

	public function set_receive_amount($receive_amount) {
		$this->set_field_value(self::$field_receive_amount, $receive_amount);
	}

	public function get_refund_num() {
		return $this->get_field_value(self::$field_refund_num);
	}

	public function set_refund_num($refund_num) {
		$this->set_field_value(self::$field_refund_num, $refund_num);
	}

	public function get_refund_amount() {
		return $this->get_field_value(self::$field_refund_amount);
	}

	public function set_refund_amount($refund_amount) {
		$this->set_field_value(self::$field_refund_amount, $refund_amount);
	}

	public function get_change_Q() {
		return $this->get_field_value(self::$field_change_Q);
	}

	public function set_change_Q($change_Q) {
		$this->set_field_value(self::$field_change_Q, $change_Q);
	}

	public function get_flow_num() {
		return $this->get_field_value(self::$field_flow_num);
	}

	public function set_flow_num($flow_num) {
		$this->set_field_value(self::$field_flow_num, $flow_num);
	}

	public function get_physical_things() {
		return $this->get_field_value(self::$field_physical_things);
	}

	public function set_physical_things($physical_things) {
		$this->set_field_value(self::$field_physical_things, $physical_things);
	}

	public function get_decorate() {
		return $this->get_field_value(self::$field_decorate);
	}

	public function set_decorate($decorate) {
		$this->set_field_value(self::$field_decorate, $decorate);
	}

	public function get_singular() {
		return $this->get_field_value(self::$field_singular);
	}

	public function set_singular($singular) {
		$this->set_field_value(self::$field_singular, $singular);
	}

	public function get_date() {
		return $this->get_field_value(self::$field_date);
	}

	public function set_date($date) {
		$this->set_field_value(self::$field_date, $date);
	}

	public function get_suser_id() {
		return $this->get_field_value(self::$field_suser_id);
	}

	public function set_suser_id($suser_id) {
		$this->set_field_value(self::$field_suser_id, $suser_id);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_user_id['name']]);
		//unset($arr[self::$field_user_name['name']]);
		//unset($arr[self::$field_team['name']]);
		//unset($arr[self::$field_group['name']]);
		//unset($arr[self::$field_group_members['name']]);
		//unset($arr[self::$field_category['name']]);
		//unset($arr[self::$field_have_received['name']]);
		//unset($arr[self::$field_receive_amount['name']]);
		//unset($arr[self::$field_refund_num['name']]);
		//unset($arr[self::$field_refund_amount['name']]);
		//unset($arr[self::$field_change_Q['name']]);
		//unset($arr[self::$field_flow_num['name']]);
		//unset($arr[self::$field_physical_things['name']]);
		//unset($arr[self::$field_decorate['name']]);
		//unset($arr[self::$field_singular['name']]);
		//unset($arr[self::$field_date['name']]);
		//unset($arr[self::$field_suser_id['name']]);
		return $arr;
	}

}

p_customerstatistics::init_schema();
