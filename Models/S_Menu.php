<?php

/**
* 菜单
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/S_Menu.php'] = 1;

use Models\Base\Model;

class S_Menu extends Model{

	public static $field_id;
	public static $field_text;
	public static $field_parent_id;
	public static $field_disabled;
	public static $field_href;
	public static $field_icon;
	public static $field_sort;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, false);
		self::$field_text = Model::define_field('text', 'string', NULL);
		self::$field_parent_id = Model::define_field('parent_id', 'int', 0);
		self::$field_disabled = Model::define_field('disabled', 'int', 0);
		self::$field_href = Model::define_field('href', 'string', NULL);
		self::$field_icon = Model::define_field('icon', 'string', NULL);
		self::$field_sort = Model::define_field('sort', 'int', 0);
		self::$MODEL_SCHEMA = Model::build_schema('S_Menu', array(
			self::$field_id,
			self::$field_text,
			self::$field_parent_id,
			self::$field_disabled,
			self::$field_href,
			self::$field_icon,
			self::$field_sort
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

	public function get_disabled() {
		return $this->get_field_value(self::$field_disabled);
	}

	public function set_disabled($disabled) {
		$this->set_field_value(self::$field_disabled, $disabled);
	}

	public function get_href() {
		return $this->get_field_value(self::$field_href);
	}

	public function set_href($href) {
		$this->set_field_value(self::$field_href, $href);
	}

	public function get_icon() {
		return $this->get_field_value(self::$field_icon);
	}

	public function set_icon($icon) {
		$this->set_field_value(self::$field_icon, $icon);
	}

	public function get_sort() {
		return $this->get_field_value(self::$field_sort);
	}

	public function set_sort($sort) {
		$this->set_field_value(self::$field_sort, $sort);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_text['name']]);
		//unset($arr[self::$field_parent_id['name']]);
		//unset($arr[self::$field_disabled['name']]);
		//unset($arr[self::$field_href['name']]);
		//unset($arr[self::$field_icon['name']]);
		//unset($arr[self::$field_sort['name']]);
		return $arr;
	}

}

S_Menu::init_schema();
