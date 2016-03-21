<?php

/**
* 员工说说/每日分享 回复
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/I_Comment.php'] = 1;

use Models\Base\Model;

class I_Comment extends Model{

	public static $field_id;
	public static $field_talk_id;
	public static $field_userid;
	public static $field_username;
	public static $field_to_userid;
	public static $field_to_username;
	public static $field_comment_id;
	public static $field_content;
	public static $field_addtime;
	public static $field_avatar;
	public static $field_exp;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_id = Model::define_primary_key('id', 'int', 0, true);
		self::$field_talk_id = Model::define_field('talk_id', 'int', 0);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$field_username = Model::define_field('username', 'string', NULL);
		self::$field_to_userid = Model::define_field('to_userid', 'int', 0);
		self::$field_to_username = Model::define_field('to_username', 'string', NULL);
		self::$field_comment_id = Model::define_field('comment_id', 'int', 0);
		self::$field_content = Model::define_field('content', 'string', NULL);
		self::$field_addtime = Model::define_field('addtime', 'datetime', NULL);
		self::$field_avatar = Model::define_field('avatar', 'string', NULL);
		self::$field_exp = Model::define_field('exp', 'int', 0);
		self::$MODEL_SCHEMA = Model::build_schema('I_Comment', array(
			self::$field_id,
			self::$field_talk_id,
			self::$field_userid,
			self::$field_username,
			self::$field_to_userid,
			self::$field_to_username,
			self::$field_comment_id,
			self::$field_content,
			self::$field_addtime,
			self::$field_avatar,
			self::$field_exp
		));
	}


	public function get_id() {
		return $this->get_field_value(self::$field_id);
	}

	public function set_id($id) {
		$this->set_field_value(self::$field_id, $id);
	}

	public function get_talk_id() {
		return $this->get_field_value(self::$field_talk_id);
	}

	public function set_talk_id($talk_id) {
		$this->set_field_value(self::$field_talk_id, $talk_id);
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

	public function get_to_userid() {
		return $this->get_field_value(self::$field_to_userid);
	}

	public function set_to_userid($to_userid) {
		$this->set_field_value(self::$field_to_userid, $to_userid);
	}

	public function get_to_username() {
		return $this->get_field_value(self::$field_to_username);
	}

	public function set_to_username($to_username) {
		$this->set_field_value(self::$field_to_username, $to_username);
	}

	public function get_comment_id() {
		return $this->get_field_value(self::$field_comment_id);
	}

	public function set_comment_id($comment_id) {
		$this->set_field_value(self::$field_comment_id, $comment_id);
	}

	public function get_content() {
		return $this->get_field_value(self::$field_content);
	}

	public function set_content($content) {
		$this->set_field_value(self::$field_content, $content);
	}

	public function get_addtime() {
		return $this->get_field_value(self::$field_addtime);
	}

	public function set_addtime($addtime) {
		$this->set_field_value(self::$field_addtime, $addtime);
	}

	public function get_avatar() {
		return $this->get_field_value(self::$field_avatar);
	}

	public function set_avatar($avatar) {
		$this->set_field_value(self::$field_avatar, $avatar);
	}

	public function get_exp() {
		return $this->get_field_value(self::$field_exp);
	}

	public function set_exp($exp) {
		$this->set_field_value(self::$field_exp, $exp);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_id['name']]);
		//unset($arr[self::$field_talk_id['name']]);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_username['name']]);
		//unset($arr[self::$field_to_userid['name']]);
		//unset($arr[self::$field_to_username['name']]);
		//unset($arr[self::$field_comment_id['name']]);
		//unset($arr[self::$field_content['name']]);
		//unset($arr[self::$field_addtime['name']]);
		//unset($arr[self::$field_avatar['name']]);
		//unset($arr[self::$field_exp['name']]);
		return $arr;
	}

}

I_Comment::init_schema();
