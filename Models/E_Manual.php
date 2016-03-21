<?php

/**
* 员工手册
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/E_Manual.php'] = 1;

use Models\Base\Model;

class E_Manual extends Model{

	public static $field_id;
	public static $field_sys_title;
	public static $field_sys_content;
	public static $field_date;
	public static $field_sys_class;
	public static $field_top;
	public static $field_file_path;
	public static $field_pdf_file_name;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_sys_title = Model::define_field('sys_title', 'string', NULL);
		self::$field_sys_content = Model::define_field('sys_content', 'string', NULL);
		self::$field_date = Model::define_field('date', 'date', NULL);
		self::$field_sys_class = Model::define_field('sys_class', 'string', NULL);
		self::$field_top = Model::define_field('top', 'int', 0);
		self::$field_file_path = Model::define_field('file_path', 'string', NULL);
		self::$field_pdf_file_name = Model::define_field('pdf_file_name', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('E_Manual', array(
			self::$field_id,
			self::$field_sys_title,
			self::$field_sys_content,
			self::$field_date,
			self::$field_sys_class,
			self::$field_top,
			self::$field_file_path,
			self::$field_pdf_file_name
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_sys_title() {
		return $this->get_field_value(self::$field_sys_title);
	}

	public function set_sys_title($sys_title) {
		$this->set_field_value(self::$field_sys_title, $sys_title);
	}

	public function get_sys_content() {
		return $this->get_field_value(self::$field_sys_content);
	}

	public function set_sys_content($sys_content) {
		$this->set_field_value(self::$field_sys_content, $sys_content);
	}

	public function get_date() {
		return $this->get_field_value(self::$field_date);
	}

	public function set_date($date) {
		$this->set_field_value(self::$field_date, $date);
	}

	public function get_sys_class() {
		return $this->get_field_value(self::$field_sys_class);
	}

	public function set_sys_class($sys_class) {
		$this->set_field_value(self::$field_sys_class, $sys_class);
	}

	public function get_top() {
		return $this->get_field_value(self::$field_top);
	}

	public function set_top($top) {
		$this->set_field_value(self::$field_top, $top);
	}

	public function get_file_path() {
		return $this->get_field_value(self::$field_file_path);
	}

	public function set_file_path($file_path) {
		$this->set_field_value(self::$field_file_path, $file_path);
	}

	public function get_pdf_file_name() {
		return $this->get_field_value(self::$field_pdf_file_name);
	}

	public function set_pdf_file_name($pdf_file_name) {
		$this->set_field_value(self::$field_pdf_file_name, $pdf_file_name);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_sys_title['name']]);
		//unset($arr[self::$field_sys_content['name']]);
		//unset($arr[self::$field_date['name']]);
		//unset($arr[self::$field_sys_class['name']]);
		//unset($arr[self::$field_top['name']]);
		//unset($arr[self::$field_file_path['name']]);
		//unset($arr[self::$field_pdf_file_name['name']]);
		return $arr;
	}

}

E_Manual::init_schema();
