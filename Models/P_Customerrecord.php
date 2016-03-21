<?php

/**
* 售后名单
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/p_customerrecord.php'] = 1;

use Models\Base\Model;

class p_customerrecord extends Model{

	public static $field_id;
	public static $field_userid;
	public static $field_username;
	public static $field_nickname;
	public static $field_toplimit;
	public static $field_finish;
	public static $field_status;
	public static $field_qqReception;
	public static $field_tmallReception_qj;
	public static $field_tmallReception_zy;
	public static $field_cShop;
	public static $field_starttime;
	public static $field_endtime;
	public static $field_lastDistribution;
	public static $field_group;
	public static $field_category;
	public static $field_team;
	public static $field_group_members;
	public static $field_channel;
	public static $field_suserid;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$field_username = Model::define_field('username', 'string', NULL);
		self::$field_nickname = Model::define_field('nickname', 'string', NULL);
		self::$field_toplimit = Model::define_field('toplimit', 'int', 0);
		self::$field_finish = Model::define_field('finish', 'int', 0);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$field_qqReception = Model::define_field('qqReception', 'int', 0);
		self::$field_tmallReception_qj = Model::define_field('tmallReception_qj', 'int', 0);
		self::$field_tmallReception_zy = Model::define_field('tmallReception_zy', 'int', 0);
		self::$field_cShop = Model::define_field('cShop', 'int', 0);
		self::$field_starttime = Model::define_field('starttime', 'string', NULL);
		self::$field_endtime = Model::define_field('endtime', 'string', NULL);
		self::$field_lastDistribution = Model::define_field('lastDistribution', 'string', 0);
		self::$field_group = Model::define_field('group', 'int', 0);
		self::$field_category = Model::define_field('category', 'string', 'C');
		self::$field_team = Model::define_field('team', 'int', 0);
		self::$field_group_members = Model::define_field('group_members', 'int', 0);
		self::$field_channel = Model::define_field('channel', 'int', 0);
		self::$field_suserid = Model::define_field('suserid', 'int', 0);
		self::$MODEL_SCHEMA = Model::build_schema('p_customerrecord', array(
			self::$field_id,
			self::$field_userid,
			self::$field_username,
			self::$field_nickname,
			self::$field_toplimit,
			self::$field_finish,
			self::$field_status,
			self::$field_qqReception,
			self::$field_tmallReception_qj,
			self::$field_tmallReception_zy,
			self::$field_cShop,
			self::$field_starttime,
			self::$field_endtime,
			self::$field_lastDistribution,
			self::$field_group,
			self::$field_category,
			self::$field_team,
			self::$field_group_members,
			self::$field_channel,
			self::$field_suserid
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_userid() {
		return $this->get_field_value(self::$field_userid);
	}

	public function set_userid($userid) {
		$this->set_field_value(self::$field_userid, $userid);
	}

	public function get_username() {
		return $this->get_field_value(self::$field_username);
	}

	public function set_username($username) {
		$this->set_field_value(self::$field_username, $username);
	}

	public function get_nickname() {
		return $this->get_field_value(self::$field_nickname);
	}

	public function set_nickname($nickname) {
		$this->set_field_value(self::$field_nickname, $nickname);
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

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function get_qqReception() {
		return $this->get_field_value(self::$field_qqReception);
	}

	public function set_qqReception($qqReception) {
		$this->set_field_value(self::$field_qqReception, $qqReception);
	}

	public function get_tmallReception_qj() {
		return $this->get_field_value(self::$field_tmallReception_qj);
	}

	public function set_tmallReception_qj($tmallReception_qj) {
		$this->set_field_value(self::$field_tmallReception_qj, $tmallReception_qj);
	}

	public function get_tmallReception_zy() {
		return $this->get_field_value(self::$field_tmallReception_zy);
	}

	public function set_tmallReception_zy($tmallReception_zy) {
		$this->set_field_value(self::$field_tmallReception_zy, $tmallReception_zy);
	}

	public function get_cShop() {
		return $this->get_field_value(self::$field_cShop);
	}

	public function set_cShop($cShop) {
		$this->set_field_value(self::$field_cShop, $cShop);
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

	public function get_group() {
		return $this->get_field_value(self::$field_group);
	}

	public function set_group($group) {
		$this->set_field_value(self::$field_group, $group);
	}

	public function get_category() {
		return $this->get_field_value(self::$field_category);
	}

	public function set_category($category) {
		$this->set_field_value(self::$field_category, $category);
	}

	public function get_team() {
		return $this->get_field_value(self::$field_team);
	}

	public function set_team($team) {
		$this->set_field_value(self::$field_team, $team);
	}

	public function get_group_members() {
		return $this->get_field_value(self::$field_group_members);
	}

	public function set_group_members($group_members) {
		$this->set_field_value(self::$field_group_members, $group_members);
	}

	public function get_channel() {
		return $this->get_field_value(self::$field_channel);
	}

	public function set_channel($channel) {
		$this->set_field_value(self::$field_channel, $channel);
	}

	public function get_suserid() {
		return $this->get_field_value(self::$field_suserid);
	}

	public function set_suserid($suserid) {
		$this->set_field_value(self::$field_suserid, $suserid);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_username['name']]);
		//unset($arr[self::$field_nickname['name']]);
		//unset($arr[self::$field_toplimit['name']]);
		//unset($arr[self::$field_finish['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_qqReception['name']]);
		//unset($arr[self::$field_tmallReception_qj['name']]);
		//unset($arr[self::$field_tmallReception_zy['name']]);
		//unset($arr[self::$field_cShop['name']]);
		//unset($arr[self::$field_starttime['name']]);
		//unset($arr[self::$field_endtime['name']]);
		//unset($arr[self::$field_lastDistribution['name']]);
		//unset($arr[self::$field_group['name']]);
		//unset($arr[self::$field_category['name']]);
		//unset($arr[self::$field_team['name']]);
		//unset($arr[self::$field_group_members['name']]);
		//unset($arr[self::$field_channel['name']]);
		//unset($arr[self::$field_suserid['name']]);
		return $arr;
	}

}

p_customerrecord::init_schema();
