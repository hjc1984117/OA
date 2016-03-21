<?php

/**
* 排班班次配置表
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/w_classes.php'] = 1;

use Models\Base\Model;

class w_classes extends Model{

	public static $field_cid;
	public static $field_stime;
	public static $field_etime;
	public static $field_remark;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_cid = Model::define_primary_key('cid', 'string', NULL, false);
		self::$field_stime = Model::define_field('stime', 'int', 0);
		self::$field_etime = Model::define_field('etime', 'int', 0);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('w_classes', array(
			self::$field_cid,
			self::$field_stime,
			self::$field_etime,
			self::$field_remark
		));
	}


	public function get_cid() {
		return $this->get_field_value(self::$field_cid);
	}

	public function set_cid($cid) {
		$this->set_field_value(self::$field_cid, $cid);
	}

	public function get_stime() {
		return $this->get_field_value(self::$field_stime);
	}

	public function set_stime($stime) {
		$this->set_field_value(self::$field_stime, $stime);
	}

	public function get_etime() {
		return $this->get_field_value(self::$field_etime);
	}

	public function set_etime($etime) {
		$this->set_field_value(self::$field_etime, $etime);
	}

	public function get_remark() {
		return $this->get_field_value(self::$field_remark);
	}

	public function set_remark($remark) {
		$this->set_field_value(self::$field_remark, $remark);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_cid['name']]);
		//unset($arr[self::$field_stime['name']]);
		//unset($arr[self::$field_etime['name']]);
		//unset($arr[self::$field_remark['name']]);
		return $arr;
	}

}

w_classes::init_schema();
