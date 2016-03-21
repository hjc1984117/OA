<?php

/**
* 员工
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/M_User.php'] = 1;

use Models\Base\Model;

class M_User extends Model{

	public static $field_userid;
	public static $field_employee_no;
	public static $field_username;
        public static $field_nickname;
        public static $field_truename;
	public static $field_dept1_id;
	public static $field_dept2_id;
	public static $field_role_id;
	public static $field_join_time;
	public static $field_positive_time;
	public static $field_sex;
	public static $field_birthday;
	public static $field_age;
	public static $field_status;
	public static $field_idcard;
	public static $field_phone;
	public static $field_qq;
        public static $field_qyqq;
	public static $field_emergency_phone;
	public static $field_emergency_contact;
	public static $field_address;
	public static $field_hukou;
	public static $field_remark;
	public static $field_leave_time;
	public static $field_work_days;
	public static $field_work_age;
	public static $field_work_shares;
	public static $field_position_shares;
	public static $field_sanction_shares;
	public static $field_current_shares;
	public static $field_permit;
	public static $field_quitProve;
        public static $field_salecount_lv;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_userid = Model::define_primary_key('userid', 'int', 0, true);
		self::$field_employee_no = Model::define_field('employee_no', 'string', NULL);
		self::$field_username = Model::define_field('username', 'string', NULL);
                self::$field_nickname = Model::define_field('nickname', 'string', NULL);
                self::$field_truename = Model::define_field('truename', 'string', NULL);
		self::$field_dept1_id = Model::define_field('dept1_id', 'int', 0);
		self::$field_dept2_id = Model::define_field('dept2_id', 'int', 0);
		self::$field_role_id = Model::define_field('role_id', 'int', 0);
		self::$field_join_time = Model::define_field('join_time', 'date', NULL);
		self::$field_positive_time = Model::define_field('positive_time', 'date', NULL);
		self::$field_sex = Model::define_field('sex', 'int', 0);
		self::$field_birthday = Model::define_field('birthday', 'date', NULL);
		self::$field_age = Model::define_field('age', 'int', 0);
		self::$field_status = Model::define_field('status', 'int', 0);
		self::$field_idcard = Model::define_field('idcard', 'string', NULL);
		self::$field_phone = Model::define_field('phone', 'string', NULL);
		self::$field_qq = Model::define_field('qq', 'string', NULL);
                self::$field_qyqq = Model::define_field('qyqq', 'string', NULL);
		self::$field_emergency_phone = Model::define_field('emergency_phone', 'string', NULL);
		self::$field_emergency_contact = Model::define_field('emergency_contact', 'string', NULL);
		self::$field_address = Model::define_field('address', 'string', NULL);
		self::$field_hukou = Model::define_field('hukou', 'string', NULL);
		self::$field_remark = Model::define_field('remark', 'string', NULL);
		self::$field_leave_time = Model::define_field('leave_time', 'date', NULL);
		self::$field_work_days = Model::define_field('work_days', 'int', 0);
		self::$field_work_age = Model::define_field('work_age', 'int', 0);
		self::$field_work_shares = Model::define_field('work_shares', 'float', 0.00);
		self::$field_position_shares = Model::define_field('position_shares', 'float', 0.00);
		self::$field_sanction_shares = Model::define_field('sanction_shares', 'float', 0.00);
		self::$field_current_shares = Model::define_field('current_shares', 'float', 0.00);
		self::$field_permit = Model::define_field('permit', 'string', NULL);
		self::$field_quitProve = Model::define_field('quitProve', 'int', 0);
                self::$field_salecount_lv = Model::define_field('salecount_lv', 'string', 'C');
		self::$MODEL_SCHEMA = Model::build_schema('M_User', array(
			self::$field_userid,
			self::$field_employee_no,
			self::$field_username,
                        self::$field_nickname,
                        self::$field_truename,
			self::$field_dept1_id,
			self::$field_dept2_id,
			self::$field_role_id,
			self::$field_join_time,
			self::$field_positive_time,
			self::$field_sex,
			self::$field_birthday,
			self::$field_age,
			self::$field_status,
			self::$field_idcard,
			self::$field_phone,
			self::$field_qq,
                        self::$field_qyqq,
			self::$field_emergency_phone,
			self::$field_emergency_contact,
			self::$field_address,
			self::$field_hukou,
			self::$field_remark,
			self::$field_leave_time,
			self::$field_work_days,
			self::$field_work_age,
			self::$field_work_shares,
			self::$field_position_shares,
			self::$field_sanction_shares,
			self::$field_current_shares,
			self::$field_permit,
			self::$field_quitProve,
                        self::$field_salecount_lv
		));
	}


	public function get_userid() {
		return $this->get_field_value(self::$field_userid);
	}

	public function set_userid($userid) {
		$this->set_field_value(self::$field_userid, $userid);
	}

	public function get_employee_no() {
		return $this->get_field_value(self::$field_employee_no);
	}

	public function set_employee_no($employee_no) {
		$this->set_field_value(self::$field_employee_no, $employee_no);
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
        
        public function get_truename() {
		return $this->get_field_value(self::$field_truename);
	}

	public function set_truename($truename) {
		$this->set_field_value(self::$field_truename, $truename);
	}

	public function get_dept1_id() {
		return $this->get_field_value(self::$field_dept1_id);
	}

	public function set_dept1_id($dept1_id) {
		$this->set_field_value(self::$field_dept1_id, $dept1_id);
	}

	public function get_dept2_id() {
		return $this->get_field_value(self::$field_dept2_id);
	}

	public function set_dept2_id($dept2_id) {
		$this->set_field_value(self::$field_dept2_id, $dept2_id);
	}

	public function get_role_id() {
		return $this->get_field_value(self::$field_role_id);
	}

	public function set_role_id($role_id) {
		$this->set_field_value(self::$field_role_id, $role_id);
	}

	public function get_join_time() {
		return $this->get_field_value(self::$field_join_time);
	}

	public function set_join_time($join_time) {
		$this->set_field_value(self::$field_join_time, $join_time);
	}

	public function get_positive_time() {
		return $this->get_field_value(self::$field_positive_time);
	}

	public function set_positive_time($positive_time) {
		$this->set_field_value(self::$field_positive_time, $positive_time);
	}

	public function get_sex() {
		return $this->get_field_value(self::$field_sex);
	}

	public function set_sex($sex) {
		$this->set_field_value(self::$field_sex, $sex);
	}

	public function get_birthday() {
		return $this->get_field_value(self::$field_birthday);
	}

	public function set_birthday($birthday) {
		$this->set_field_value(self::$field_birthday, $birthday);
	}

	public function get_age() {
		return $this->get_field_value(self::$field_age);
	}

	public function set_age($age) {
		$this->set_field_value(self::$field_age, $age);
	}

	public function get_status() {
		return $this->get_field_value(self::$field_status);
	}

	public function set_status($status) {
		$this->set_field_value(self::$field_status, $status);
	}

	public function get_idcard() {
		return $this->get_field_value(self::$field_idcard);
	}

	public function set_idcard($idcard) {
		$this->set_field_value(self::$field_idcard, $idcard);
	}

	public function get_phone() {
		return $this->get_field_value(self::$field_phone);
	}

	public function set_phone($phone) {
		$this->set_field_value(self::$field_phone, $phone);
	}

	public function get_qq() {
		return $this->get_field_value(self::$field_qq);
	}

	public function set_qq($qq) {
		$this->set_field_value(self::$field_qq, $qq);
	}
        
        public function get_qyqq() {
		return $this->get_field_value(self::$field_qyqq);
	}

	public function set_qyqq($qyqq) {
		$this->set_field_value(self::$field_qyqq, $qyqq);
	}

	public function get_emergency_phone() {
		return $this->get_field_value(self::$field_emergency_phone);
	}

	public function set_emergency_phone($emergency_phone) {
		$this->set_field_value(self::$field_emergency_phone, $emergency_phone);
	}

	public function get_emergency_contact() {
		return $this->get_field_value(self::$field_emergency_contact);
	}

	public function set_emergency_contact($emergency_contact) {
		$this->set_field_value(self::$field_emergency_contact, $emergency_contact);
	}

	public function get_address() {
		return $this->get_field_value(self::$field_address);
	}

	public function set_address($address) {
		$this->set_field_value(self::$field_address, $address);
	}

	public function get_hukou() {
		return $this->get_field_value(self::$field_hukou);
	}

	public function set_hukou($hukou) {
		$this->set_field_value(self::$field_hukou, $hukou);
	}

	public function get_remark() {
		return $this->get_field_value(self::$field_remark);
	}

	public function set_remark($remark) {
		$this->set_field_value(self::$field_remark, $remark);
	}

	public function get_leave_time() {
		return $this->get_field_value(self::$field_leave_time);
	}

	public function set_leave_time($leave_time) {
		$this->set_field_value(self::$field_leave_time, $leave_time);
	}

	public function get_work_days() {
		return $this->get_field_value(self::$field_work_days);
	}

	public function set_work_days($work_days) {
		$this->set_field_value(self::$field_work_days, $work_days);
	}

	public function get_work_age() {
		return $this->get_field_value(self::$field_work_age);
	}

	public function set_work_age($work_age) {
		$this->set_field_value(self::$field_work_age, $work_age);
	}

	public function get_work_shares() {
		return $this->get_field_value(self::$field_work_shares);
	}

	public function set_work_shares($work_shares) {
		$this->set_field_value(self::$field_work_shares, $work_shares);
	}

	public function get_position_shares() {
		return $this->get_field_value(self::$field_position_shares);
	}

	public function set_position_shares($position_shares) {
		$this->set_field_value(self::$field_position_shares, $position_shares);
	}

	public function get_sanction_shares() {
		return $this->get_field_value(self::$field_sanction_shares);
	}

	public function set_sanction_shares($sanction_shares) {
		$this->set_field_value(self::$field_sanction_shares, $sanction_shares);
	}

	public function get_current_shares() {
		return $this->get_field_value(self::$field_current_shares);
	}

	public function set_current_shares($current_shares) {
		$this->set_field_value(self::$field_current_shares, $current_shares);
	}

	public function get_permit() {
		return $this->get_field_value(self::$field_permit);
	}

	public function set_permit($permit) {
		$this->set_field_value(self::$field_permit, $permit);
	}

	public function get_quitProve() {
		return $this->get_field_value(self::$field_quitProve);
	}

	public function set_quitProve($quitProve) {
		$this->set_field_value(self::$field_quitProve, $quitProve);
	}
        
        public function get_salecount_lv() {
		return $this->get_field_value(self::$field_salecount_lv);
	}

	public function set_salecount_lv($salecount_lv) {
		$this->set_field_value(self::$field_salecount_lv, $salecount_lv);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_employee_no['name']]);
		//unset($arr[self::$field_username['name']]);
                //unset($arr[self::$field_truename['name']]);
		//unset($arr[self::$field_dept1_id['name']]);
		//unset($arr[self::$field_dept2_id['name']]);
		//unset($arr[self::$field_role_id['name']]);
		//unset($arr[self::$field_join_time['name']]);
		//unset($arr[self::$field_positive_time['name']]);
		//unset($arr[self::$field_sex['name']]);
		//unset($arr[self::$field_birthday['name']]);
		//unset($arr[self::$field_age['name']]);
		//unset($arr[self::$field_status['name']]);
		//unset($arr[self::$field_idcard['name']]);
		//unset($arr[self::$field_phone['name']]);
		//unset($arr[self::$field_qq['name']]);
                //unset($arr[self::$field_qyqq['name']]);
		//unset($arr[self::$field_emergency_phone['name']]);
		//unset($arr[self::$field_emergency_contact['name']]);
		//unset($arr[self::$field_address['name']]);
		//unset($arr[self::$field_hukou['name']]);
		//unset($arr[self::$field_remark['name']]);
		//unset($arr[self::$field_leave_time['name']]);
		//unset($arr[self::$field_work_days['name']]);
		//unset($arr[self::$field_work_age['name']]);
		//unset($arr[self::$field_work_shares['name']]);
		//unset($arr[self::$field_position_shares['name']]);
		//unset($arr[self::$field_sanction_shares['name']]);
		//unset($arr[self::$field_current_shares['name']]);
		//unset($arr[self::$field_permit['name']]);
		//unset($arr[self::$field_quitProve['name']]);
                //unset($arr[self::$field_salecount_lv['name']]);
		return $arr;
	}

}

M_User::init_schema();
