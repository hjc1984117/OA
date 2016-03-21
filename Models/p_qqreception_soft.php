<?php

/**
* QQ接入
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_qqreception_soft.php'] = 1;

use Models\Base\Model;

class p_qqreception_soft extends Model{

	public static $field_id;
	public static $field_status;
	public static $field_status0;
	public static $field_addtime;
	public static $field_presales;
	public static $field_presales_id;
	public static $field_toplimit;
	public static $field_finish;
	public static $field_starttime;
	public static $field_endtime;
	public static $field_lastDistribution;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$field_status0 = Model::define_field('status0', 'int', 0);
		self::$field_addtime = Model::define_field('addtime', 'datetime', NULL);
		self::$field_presales = Model::define_field('presales', 'string', NULL);
		self::$field_presales_id = Model::define_field('presales_id', 'int', 0);
		self::$field_toplimit = Model::define_field('toplimit', 'int', 0);
		self::$field_finish = Model::define_field('finish', 'int', 0);
		self::$field_starttime = Model::define_field('starttime', 'string', NULL);
		self::$field_endtime = Model::define_field('endtime', 'string', NULL);
		self::$field_lastDistribution = Model::define_field('lastDistribution', 'string', 0);
		self::$MODEL_SCHEMA = Model::build_schema('p_qqreception_soft', array(
			self::$field_id,
			self::$field_status,
			self::$field_status0,
			self::$field_addtime,
			self::$field_presales,
			self::$field_presales_id,
			self::$field_toplimit,
			self::$field_finish,
			self::$field_starttime,
			self::$field_endtime,
			self::$field_lastDistribution
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function get_status0() {
		return $this->get_field_value(self::$field_status0);
	}

	public function set_status0($status0) {
		$this->set_field_value(self::$field_status0, $status0);
	}

	public function get_addtime() {
		return $this->get_field_value(self::$field_addtime);
	}

	public function set_addtime($addtime) {
		$this->set_field_value(self::$field_addtime, $addtime);
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

	public function get_toplimit() {
		return $this->get_field_value(self::$field_toplimit);
	}

	public function set_toplimit($toplimit) {
		$this->set_field_value(self::$field_toplimit, $toplimit);
	}

	public function get_finish() {
		return $this->get_field_value(self::$field_finish);
	}

	public function set_finish($finish) {
		$this->set_field_value(self::$field_finish, $finish);
	}

	public function get_starttime() {
		return $this->get_field_value(self::$field_starttime);
	}

	public function set_starttime($starttime) {
		$this->set_field_value(self::$field_starttime, $starttime);
	}

	public function get_endtime() {
		return $this->get_field_value(self::$field_endtime);
	}

	public function set_endtime($endtime) {
		$this->set_field_value(self::$field_endtime, $endtime);
	}

	public function get_lastDistribution() {
		return $this->get_field_value(self::$field_lastDistribution);
	}

	public function set_lastDistribution($lastDistribution) {
		$this->set_field_value(self::$field_lastDistribution, $lastDistribution);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_status0['name']]);
		//unset($arr[self::$field_addtime['name']]);
		//unset($arr[self::$field_presales['name']]);
		//unset($arr[self::$field_presales_id['name']]);
		//unset($arr[self::$field_toplimit['name']]);
		//unset($arr[self::$field_finish['name']]);
		//unset($arr[self::$field_starttime['name']]);
		//unset($arr[self::$field_endtime['name']]);
		//unset($arr[self::$field_lastDistribution['name']]);
		return $arr;
	}

}

p_qqreception_soft::init_schema();
