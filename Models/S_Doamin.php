<?php

/**
* 
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/S_Doamin.php'] = 1;

use Models\Base\Model;

class S_Doamin extends Model{

	public static $field_id;
	public static $field_name;
	public static $field_owner;
	public static $field_dueDate;
	public static $field_useed;
	public static $field_isBurn;
	public static $field_recordPerson;
	public static $field_recordSituation;
	public static $field_recordType;
	public static $field_nameType;
	public static $field_nameBusiness;
	public static $field_buyAccount;
	public static $field_category;
	public static $field_service;
	public static $field_ipAddress;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_name = Model::define_field('name', 'string', NULL);
		self::$field_owner = Model::define_field('owner', 'string', NULL);
		self::$field_dueDate = Model::define_field('dueDate', 'date', NULL);
		self::$field_useed = Model::define_field('useed', 'string', NULL);
		self::$field_isBurn = Model::define_field('isBurn', 'string', 0);
		self::$field_recordPerson = Model::define_field('recordPerson', 'string', NULL);
		self::$field_recordSituation = Model::define_field('recordSituation', 'string', NULL);
		self::$field_recordType = Model::define_field('recordType', 'string', NULL);
		self::$field_nameType = Model::define_field('nameType', 'string', NULL);
		self::$field_nameBusiness = Model::define_field('nameBusiness', 'string', NULL);
		self::$field_buyAccount = Model::define_field('buyAccount', 'string', NULL);
		self::$field_category = Model::define_field('category', 'string', NULL);
		self::$field_service = Model::define_field('service', 'string', NULL);
		self::$field_ipAddress = Model::define_field('ipAddress', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('S_Doamin', array(
			self::$field_id,
			self::$field_name,
			self::$field_owner,
			self::$field_dueDate,
			self::$field_useed,
			self::$field_isBurn,
			self::$field_recordPerson,
			self::$field_recordSituation,
			self::$field_recordType,
			self::$field_nameType,
			self::$field_nameBusiness,
			self::$field_buyAccount,
			self::$field_category,
			self::$field_service,
			self::$field_ipAddress
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_name() {
		return $this->get_field_value(self::$field_name);
	}

	public function set_name($name) {
		$this->set_field_value(self::$field_name, $name);
	}

	public function get_owner() {
		return $this->get_field_value(self::$field_owner);
	}

	public function set_owner($owner) {
		$this->set_field_value(self::$field_owner, $owner);
	}

	public function get_dueDate() {
		return $this->get_field_value(self::$field_dueDate);
	}

	public function set_dueDate($dueDate) {
		$this->set_field_value(self::$field_dueDate, $dueDate);
	}

	public function get_useed() {
		return $this->get_field_value(self::$field_useed);
	}

	public function set_useed($useed) {
		$this->set_field_value(self::$field_useed, $useed);
	}

	public function get_isBurn() {
		return $this->get_field_value(self::$field_isBurn);
	}

	public function set_isBurn($isBurn) {
		$this->set_field_value(self::$field_isBurn, $isBurn);
	}

	public function get_recordPerson() {
		return $this->get_field_value(self::$field_recordPerson);
	}

	public function set_recordPerson($recordPerson) {
		$this->set_field_value(self::$field_recordPerson, $recordPerson);
	}

	public function get_recordSituation() {
		return $this->get_field_value(self::$field_recordSituation);
	}

	public function set_recordSituation($recordSituation) {
		$this->set_field_value(self::$field_recordSituation, $recordSituation);
	}

	public function get_recordType() {
		return $this->get_field_value(self::$field_recordType);
	}

	public function set_recordType($recordType) {
		$this->set_field_value(self::$field_recordType, $recordType);
	}

	public function get_nameType() {
		return $this->get_field_value(self::$field_nameType);
	}

	public function set_nameType($nameType) {
		$this->set_field_value(self::$field_nameType, $nameType);
	}

	public function get_nameBusiness() {
		return $this->get_field_value(self::$field_nameBusiness);
	}

	public function set_nameBusiness($nameBusiness) {
		$this->set_field_value(self::$field_nameBusiness, $nameBusiness);
	}

	public function get_buyAccount() {
		return $this->get_field_value(self::$field_buyAccount);
	}

	public function set_buyAccount($buyAccount) {
		$this->set_field_value(self::$field_buyAccount, $buyAccount);
	}

	public function get_category() {
		return $this->get_field_value(self::$field_category);
	}

	public function set_category($category) {
		$this->set_field_value(self::$field_category, $category);
	}

	public function get_service() {
		return $this->get_field_value(self::$field_service);
	}

	public function set_service($service) {
		$this->set_field_value(self::$field_service, $service);
	}

	public function get_ipAddress() {
		return $this->get_field_value(self::$field_ipAddress);
	}

	public function set_ipAddress($ipAddress) {
		$this->set_field_value(self::$field_ipAddress, $ipAddress);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_name['name']]);
		//unset($arr[self::$field_owner['name']]);
		//unset($arr[self::$field_dueDate['name']]);
		//unset($arr[self::$field_useed['name']]);
		//unset($arr[self::$field_isBurn['name']]);
		//unset($arr[self::$field_recordPerson['name']]);
		//unset($arr[self::$field_recordSituation['name']]);
		//unset($arr[self::$field_recordType['name']]);
		//unset($arr[self::$field_nameType['name']]);
		//unset($arr[self::$field_nameBusiness['name']]);
		//unset($arr[self::$field_buyAccount['name']]);
		//unset($arr[self::$field_category['name']]);
		//unset($arr[self::$field_service['name']]);
		//unset($arr[self::$field_ipAddress['name']]);
		return $arr;
	}

}

S_Doamin::init_schema();
