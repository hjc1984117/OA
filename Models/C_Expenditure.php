<?php

/**
* 固定支出明细
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/C_Expenditure.php'] = 1;

use Models\Base\Model;

class C_Expenditure extends Model{

	public static $field_id;
	public static $field_date;
	public static $field_e_project;
	public static $field_e_detailed;
	public static $field_handling;
	public static $field_handling_id;
	public static $field_money_sum;
	public static $field_e_time;
	public static $field_remark;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_date = Model::define_field('date', 'datetime', NULL);
		self::$field_e_project = Model::define_field('e_project', 'string', NULL);
		self::$field_e_detailed = Model::define_field('e_detailed', 'string', NULL);
		self::$field_handling = Model::define_field('handling', 'string', NULL);
		self::$field_handling_id = Model::define_field('handling_id', 'int', 0);
		self::$field_money_sum = Model::define_field('money_sum', 'float', 0.00);
		self::$field_e_time = Model::define_field('e_time', 'date', NULL);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('C_Expenditure', array(
			self::$field_id,
			self::$field_date,
			self::$field_e_project,
			self::$field_e_detailed,
			self::$field_handling,
			self::$field_handling_id,
			self::$field_money_sum,
			self::$field_e_time,
			self::$field_remark
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_date() {
		return $this->get_field_value(self::$field_date);
	}

	public function set_date($date) {
		$this->set_field_value(self::$field_date, $date);
	}

	public function get_e_project() {
		return $this->get_field_value(self::$field_e_project);
	}

	public function set_e_project($e_project) {
		$this->set_field_value(self::$field_e_project, $e_project);
	}

	public function get_e_detailed() {
		return $this->get_field_value(self::$field_e_detailed);
	}

	public function set_e_detailed($e_detailed) {
		$this->set_field_value(self::$field_e_detailed, $e_detailed);
	}

	public function get_handling() {
		return $this->get_field_value(self::$field_handling);
	}

	public function set_handling($handling) {
		$this->set_field_value(self::$field_handling, $handling);
	}

	public function get_handling_id() {
		return $this->get_field_value(self::$field_handling_id);
	}

	public function set_handling_id($handling_id) {
		$this->set_field_value(self::$field_handling_id, $handling_id);
	}

	public function get_money_sum() {
		return $this->get_field_value(self::$field_money_sum);
	}

	public function set_money_sum($money_sum) {
		$this->set_field_value(self::$field_money_sum, $money_sum);
	}

	public function get_e_time() {
		return $this->get_field_value(self::$field_e_time);
	}

	public function set_e_time($e_time) {
		$this->set_field_value(self::$field_e_time, $e_time);
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
		//unset($arr[self::$field_date['name']]);
		//unset($arr[self::$field_e_project['name']]);
		//unset($arr[self::$field_e_detailed['name']]);
		//unset($arr[self::$field_handling['name']]);
		//unset($arr[self::$field_handling_id['name']]);
		//unset($arr[self::$field_money_sum['name']]);
		//unset($arr[self::$field_e_time['name']]);
		//unset($arr[self::$field_remark['name']]);
		return $arr;
	}

}

C_Expenditure::init_schema();
