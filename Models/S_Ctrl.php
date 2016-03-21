<?php

/**
* 控件
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/S_Ctrl.php'] = 1;

use Models\Base\Model;

class S_Ctrl extends Model{

	public static $field_id;
	public static $field_menu_id;
	public static $field_text;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, false);
		self::$field_menu_id = Model::define_field('menu_id', 'int', 0);
		self::$field_text = Model::define_field('text', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('S_Ctrl', array(
			self::$field_id,
			self::$field_menu_id,
			self::$field_text
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_menu_id() {
		return $this->get_field_value(self::$field_menu_id);
	}

	public function set_menu_id($menu_id) {
		$this->set_field_value(self::$field_menu_id, $menu_id);
	}

	public function get_text() {
		return $this->get_field_value(self::$field_text);
	}

	public function set_text($text) {
		$this->set_field_value(self::$field_text, $text);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_menu_id['name']]);
		//unset($arr[self::$field_text['name']]);
		return $arr;
	}

}

S_Ctrl::init_schema();
