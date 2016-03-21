<?php

/**
* 部门
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/M_Dept.php'] = 1;

use Models\Base\Model;

class M_Dept extends Model{

	public static $field_id;
	public static $field_text;
	public static $field_parent_id;
	public static $field_permit;
	public static $field_icon;
	public static $field_order;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, false);
		self::$field_text = Model::define_field('text', 'string', NULL);
		self::$field_parent_id = Model::define_field('parent_id', 'int', 0);
		self::$field_permit = Model::define_field('permit', 'string', NULL);
		self::$field_icon = Model::define_field('icon', 'string', NULL);
		self::$field_order = Model::define_field('order', 'int', 0);
		self::$MODEL_SCHEMA = Model::build_schema('M_Dept', array(
			self::$field_id,
			self::$field_text,
			self::$field_parent_id,
			self::$field_permit,
			self::$field_icon,
			self::$field_order
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_text() {
		return $this->get_field_value(self::$field_text);
	}

	public function set_text($text) {
		$this->set_field_value(self::$field_text, $text);
	}

	public function get_parent_id() {
		return $this->get_field_value(self::$field_parent_id);
	}

	public function set_parent_id($parent_id) {
		$this->set_field_value(self::$field_parent_id, $parent_id);
	}

	public function get_permit() {
		return $this->get_field_value(self::$field_permit);
	}

	public function set_permit($permit) {
		$this->set_field_value(self::$field_permit, $permit);
	}

	public function get_icon() {
		return $this->get_field_value(self::$field_icon);
	}

	public function set_icon($icon) {
		$this->set_field_value(self::$field_icon, $icon);
	}

	public function get_order() {
		return $this->get_field_value(self::$field_order);
	}

	public function set_order($order) {
		$this->set_field_value(self::$field_order, $order);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_text['name']]);
		//unset($arr[self::$field_parent_id['name']]);
		//unset($arr[self::$field_permit['name']]);
		//unset($arr[self::$field_icon['name']]);
		//unset($arr[self::$field_order['name']]);
		return $arr;
	}

}

M_Dept::init_schema();
