<?php

/**
* QQ接入中渠道网址对应表
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_channel_site_soft.php'] = 1;

use Models\Base\Model;

class p_channel_site_soft extends Model{

	public static $field_id;
	public static $field_channel;
	public static $field_address;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_channel = Model::define_field('channel', 'string', NULL);
		self::$field_address = Model::define_field('address', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('p_channel_site_soft', array(
			self::$field_id,
			self::$field_channel,
			self::$field_address
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_channel() {
		return $this->get_field_value(self::$field_channel);
	}

	public function set_channel($channel) {
		$this->set_field_value(self::$field_channel, $channel);
	}

	public function get_address() {
		return $this->get_field_value(self::$field_address);
	}

	public function set_address($address) {
		$this->set_field_value(self::$field_address, $address);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_channel['name']]);
		//unset($arr[self::$field_address['name']]);
		return $arr;
	}

}

p_channel_site_soft::init_schema();
