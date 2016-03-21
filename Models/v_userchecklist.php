<?php

/**
* VIEW
*
* @author 自动生成的实体类
* @copyright (c) 2015, 星密码集团
* @version 1.0
*/

namespace Models;

$GLOBALS['/Models/v_userchecklist.php'] = 1;

use Models\Base\Model;

class v_userchecklist extends Model{

	public static $field_dy;
	public static $field_userid;
	public static $field_dept_id;
	public static $field_m1;
	public static $field_z1;
	public static $field_m2;
	public static $field_z2;
	public static $field_m3;
	public static $field_z3;
	public static $field_m4;
	public static $field_z4;
	public static $field_m5;
	public static $field_z5;
	public static $field_m6;
	public static $field_z6;
	public static $field_m7;
	public static $field_z7;
	public static $field_m8;
	public static $field_z8;
	public static $field_m9;
	public static $field_z9;
	public static $field_m10;
	public static $field_z10;
	public static $field_m11;
	public static $field_z11;
	public static $field_m12;
	public static $field_z12;
	public static $field_m13;
	public static $field_z13;
	public static $field_m14;
	public static $field_z14;
	public static $field_m15;
	public static $field_z15;
	public static $field_m16;
	public static $field_z16;
	public static $field_m17;
	public static $field_z17;
	public static $field_m18;
	public static $field_z18;
	public static $field_m19;
	public static $field_z19;
	public static $field_m20;
	public static $field_z20;
	public static $field_m21;
	public static $field_z21;
	public static $field_m22;
	public static $field_z22;
	public static $field_m23;
	public static $field_z23;
	public static $field_m24;
	public static $field_z24;
	public static $field_m25;
	public static $field_z25;
	public static $field_m26;
	public static $field_z26;
	public static $field_m27;
	public static $field_z27;
	public static $field_m28;
	public static $field_z28;
	public static $field_m29;
	public static $field_z29;
	public static $field_m30;
	public static $field_z30;
	public static $field_m31;
	public static $field_z31;
	public static $field_es32;
	public static $field_ext;
	public static $MODEL_SCHEMA;

	static function init_schema() {
		self::$field_dy = Model::define_field('dy', 'date', NULL);
		self::$field_userid = Model::define_field('userid', 'int', 0);
		self::$field_dept_id = Model::define_field('dept_id', 'int', 0);
		self::$field_m1 = Model::define_field('m1', 'string', NULL);
		self::$field_z1 = Model::define_field('z1', 'string', NULL);
		self::$field_m2 = Model::define_field('m2', 'string', NULL);
		self::$field_z2 = Model::define_field('z2', 'string', NULL);
		self::$field_m3 = Model::define_field('m3', 'string', NULL);
		self::$field_z3 = Model::define_field('z3', 'string', NULL);
		self::$field_m4 = Model::define_field('m4', 'string', NULL);
		self::$field_z4 = Model::define_field('z4', 'string', NULL);
		self::$field_m5 = Model::define_field('m5', 'string', NULL);
		self::$field_z5 = Model::define_field('z5', 'string', NULL);
		self::$field_m6 = Model::define_field('m6', 'string', NULL);
		self::$field_z6 = Model::define_field('z6', 'string', NULL);
		self::$field_m7 = Model::define_field('m7', 'string', NULL);
		self::$field_z7 = Model::define_field('z7', 'string', NULL);
		self::$field_m8 = Model::define_field('m8', 'string', NULL);
		self::$field_z8 = Model::define_field('z8', 'string', NULL);
		self::$field_m9 = Model::define_field('m9', 'string', NULL);
		self::$field_z9 = Model::define_field('z9', 'string', NULL);
		self::$field_m10 = Model::define_field('m10', 'string', NULL);
		self::$field_z10 = Model::define_field('z10', 'string', NULL);
		self::$field_m11 = Model::define_field('m11', 'string', NULL);
		self::$field_z11 = Model::define_field('z11', 'string', NULL);
		self::$field_m12 = Model::define_field('m12', 'string', NULL);
		self::$field_z12 = Model::define_field('z12', 'string', NULL);
		self::$field_m13 = Model::define_field('m13', 'string', NULL);
		self::$field_z13 = Model::define_field('z13', 'string', NULL);
		self::$field_m14 = Model::define_field('m14', 'string', NULL);
		self::$field_z14 = Model::define_field('z14', 'string', NULL);
		self::$field_m15 = Model::define_field('m15', 'string', NULL);
		self::$field_z15 = Model::define_field('z15', 'string', NULL);
		self::$field_m16 = Model::define_field('m16', 'string', NULL);
		self::$field_z16 = Model::define_field('z16', 'string', NULL);
		self::$field_m17 = Model::define_field('m17', 'string', NULL);
		self::$field_z17 = Model::define_field('z17', 'string', NULL);
		self::$field_m18 = Model::define_field('m18', 'string', NULL);
		self::$field_z18 = Model::define_field('z18', 'string', NULL);
		self::$field_m19 = Model::define_field('m19', 'string', NULL);
		self::$field_z19 = Model::define_field('z19', 'string', NULL);
		self::$field_m20 = Model::define_field('m20', 'string', NULL);
		self::$field_z20 = Model::define_field('z20', 'string', NULL);
		self::$field_m21 = Model::define_field('m21', 'string', NULL);
		self::$field_z21 = Model::define_field('z21', 'string', NULL);
		self::$field_m22 = Model::define_field('m22', 'string', NULL);
		self::$field_z22 = Model::define_field('z22', 'string', NULL);
		self::$field_m23 = Model::define_field('m23', 'string', NULL);
		self::$field_z23 = Model::define_field('z23', 'string', NULL);
		self::$field_m24 = Model::define_field('m24', 'string', NULL);
		self::$field_z24 = Model::define_field('z24', 'string', NULL);
		self::$field_m25 = Model::define_field('m25', 'string', NULL);
		self::$field_z25 = Model::define_field('z25', 'string', NULL);
		self::$field_m26 = Model::define_field('m26', 'string', NULL);
		self::$field_z26 = Model::define_field('z26', 'string', NULL);
		self::$field_m27 = Model::define_field('m27', 'string', NULL);
		self::$field_z27 = Model::define_field('z27', 'string', NULL);
		self::$field_m28 = Model::define_field('m28', 'string', NULL);
		self::$field_z28 = Model::define_field('z28', 'string', NULL);
		self::$field_m29 = Model::define_field('m29', 'string', NULL);
		self::$field_z29 = Model::define_field('z29', 'string', NULL);
		self::$field_m30 = Model::define_field('m30', 'string', NULL);
		self::$field_z30 = Model::define_field('z30', 'string', NULL);
		self::$field_m31 = Model::define_field('m31', 'string', NULL);
		self::$field_z31 = Model::define_field('z31', 'string', NULL);
		self::$field_es32 = Model::define_field('es32', 'string', NULL);
		self::$field_ext = Model::define_field('ext', 'string', NULL);
		self::$MODEL_SCHEMA = Model::build_schema('v_userchecklist', array(
			self::$field_dy,
			self::$field_userid,
			self::$field_dept_id,
			self::$field_m1,
			self::$field_z1,
			self::$field_m2,
			self::$field_z2,
			self::$field_m3,
			self::$field_z3,
			self::$field_m4,
			self::$field_z4,
			self::$field_m5,
			self::$field_z5,
			self::$field_m6,
			self::$field_z6,
			self::$field_m7,
			self::$field_z7,
			self::$field_m8,
			self::$field_z8,
			self::$field_m9,
			self::$field_z9,
			self::$field_m10,
			self::$field_z10,
			self::$field_m11,
			self::$field_z11,
			self::$field_m12,
			self::$field_z12,
			self::$field_m13,
			self::$field_z13,
			self::$field_m14,
			self::$field_z14,
			self::$field_m15,
			self::$field_z15,
			self::$field_m16,
			self::$field_z16,
			self::$field_m17,
			self::$field_z17,
			self::$field_m18,
			self::$field_z18,
			self::$field_m19,
			self::$field_z19,
			self::$field_m20,
			self::$field_z20,
			self::$field_m21,
			self::$field_z21,
			self::$field_m22,
			self::$field_z22,
			self::$field_m23,
			self::$field_z23,
			self::$field_m24,
			self::$field_z24,
			self::$field_m25,
			self::$field_z25,
			self::$field_m26,
			self::$field_z26,
			self::$field_m27,
			self::$field_z27,
			self::$field_m28,
			self::$field_z28,
			self::$field_m29,
			self::$field_z29,
			self::$field_m30,
			self::$field_z30,
			self::$field_m31,
			self::$field_z31,
			self::$field_es32,
			self::$field_ext
		));
	}


	public function get_dy() {
		return $this->get_field_value(self::$field_dy);
	}

	public function set_dy($dy) {
		$this->set_field_value(self::$field_dy, $dy);
	}

	public function get_userid() {
		return $this->get_field_value(self::$field_userid);
	}

	public function set_userid($userid) {
		$this->set_field_value(self::$field_userid, $userid);
	}

	public function get_dept_id() {
		return $this->get_field_value(self::$field_dept_id);
	}

	public function set_dept_id($dept_id) {
		$this->set_field_value(self::$field_dept_id, $dept_id);
	}

	public function get_m1() {
		return $this->get_field_value(self::$field_m1);
	}

	public function set_m1($m1) {
		$this->set_field_value(self::$field_m1, $m1);
	}

	public function get_z1() {
		return $this->get_field_value(self::$field_z1);
	}

	public function set_z1($z1) {
		$this->set_field_value(self::$field_z1, $z1);
	}

	public function get_m2() {
		return $this->get_field_value(self::$field_m2);
	}

	public function set_m2($m2) {
		$this->set_field_value(self::$field_m2, $m2);
	}

	public function get_z2() {
		return $this->get_field_value(self::$field_z2);
	}

	public function set_z2($z2) {
		$this->set_field_value(self::$field_z2, $z2);
	}

	public function get_m3() {
		return $this->get_field_value(self::$field_m3);
	}

	public function set_m3($m3) {
		$this->set_field_value(self::$field_m3, $m3);
	}

	public function get_z3() {
		return $this->get_field_value(self::$field_z3);
	}

	public function set_z3($z3) {
		$this->set_field_value(self::$field_z3, $z3);
	}

	public function get_m4() {
		return $this->get_field_value(self::$field_m4);
	}

	public function set_m4($m4) {
		$this->set_field_value(self::$field_m4, $m4);
	}

	public function get_z4() {
		return $this->get_field_value(self::$field_z4);
	}

	public function set_z4($z4) {
		$this->set_field_value(self::$field_z4, $z4);
	}

	public function get_m5() {
		return $this->get_field_value(self::$field_m5);
	}

	public function set_m5($m5) {
		$this->set_field_value(self::$field_m5, $m5);
	}

	public function get_z5() {
		return $this->get_field_value(self::$field_z5);
	}

	public function set_z5($z5) {
		$this->set_field_value(self::$field_z5, $z5);
	}

	public function get_m6() {
		return $this->get_field_value(self::$field_m6);
	}

	public function set_m6($m6) {
		$this->set_field_value(self::$field_m6, $m6);
	}

	public function get_z6() {
		return $this->get_field_value(self::$field_z6);
	}

	public function set_z6($z6) {
		$this->set_field_value(self::$field_z6, $z6);
	}

	public function get_m7() {
		return $this->get_field_value(self::$field_m7);
	}

	public function set_m7($m7) {
		$this->set_field_value(self::$field_m7, $m7);
	}

	public function get_z7() {
		return $this->get_field_value(self::$field_z7);
	}

	public function set_z7($z7) {
		$this->set_field_value(self::$field_z7, $z7);
	}

	public function get_m8() {
		return $this->get_field_value(self::$field_m8);
	}

	public function set_m8($m8) {
		$this->set_field_value(self::$field_m8, $m8);
	}

	public function get_z8() {
		return $this->get_field_value(self::$field_z8);
	}

	public function set_z8($z8) {
		$this->set_field_value(self::$field_z8, $z8);
	}

	public function get_m9() {
		return $this->get_field_value(self::$field_m9);
	}

	public function set_m9($m9) {
		$this->set_field_value(self::$field_m9, $m9);
	}

	public function get_z9() {
		return $this->get_field_value(self::$field_z9);
	}

	public function set_z9($z9) {
		$this->set_field_value(self::$field_z9, $z9);
	}

	public function get_m10() {
		return $this->get_field_value(self::$field_m10);
	}

	public function set_m10($m10) {
		$this->set_field_value(self::$field_m10, $m10);
	}

	public function get_z10() {
		return $this->get_field_value(self::$field_z10);
	}

	public function set_z10($z10) {
		$this->set_field_value(self::$field_z10, $z10);
	}

	public function get_m11() {
		return $this->get_field_value(self::$field_m11);
	}

	public function set_m11($m11) {
		$this->set_field_value(self::$field_m11, $m11);
	}

	public function get_z11() {
		return $this->get_field_value(self::$field_z11);
	}

	public function set_z11($z11) {
		$this->set_field_value(self::$field_z11, $z11);
	}

	public function get_m12() {
		return $this->get_field_value(self::$field_m12);
	}

	public function set_m12($m12) {
		$this->set_field_value(self::$field_m12, $m12);
	}

	public function get_z12() {
		return $this->get_field_value(self::$field_z12);
	}

	public function set_z12($z12) {
		$this->set_field_value(self::$field_z12, $z12);
	}

	public function get_m13() {
		return $this->get_field_value(self::$field_m13);
	}

	public function set_m13($m13) {
		$this->set_field_value(self::$field_m13, $m13);
	}

	public function get_z13() {
		return $this->get_field_value(self::$field_z13);
	}

	public function set_z13($z13) {
		$this->set_field_value(self::$field_z13, $z13);
	}

	public function get_m14() {
		return $this->get_field_value(self::$field_m14);
	}

	public function set_m14($m14) {
		$this->set_field_value(self::$field_m14, $m14);
	}

	public function get_z14() {
		return $this->get_field_value(self::$field_z14);
	}

	public function set_z14($z14) {
		$this->set_field_value(self::$field_z14, $z14);
	}

	public function get_m15() {
		return $this->get_field_value(self::$field_m15);
	}

	public function set_m15($m15) {
		$this->set_field_value(self::$field_m15, $m15);
	}

	public function get_z15() {
		return $this->get_field_value(self::$field_z15);
	}

	public function set_z15($z15) {
		$this->set_field_value(self::$field_z15, $z15);
	}

	public function get_m16() {
		return $this->get_field_value(self::$field_m16);
	}

	public function set_m16($m16) {
		$this->set_field_value(self::$field_m16, $m16);
	}

	public function get_z16() {
		return $this->get_field_value(self::$field_z16);
	}

	public function set_z16($z16) {
		$this->set_field_value(self::$field_z16, $z16);
	}

	public function get_m17() {
		return $this->get_field_value(self::$field_m17);
	}

	public function set_m17($m17) {
		$this->set_field_value(self::$field_m17, $m17);
	}

	public function get_z17() {
		return $this->get_field_value(self::$field_z17);
	}

	public function set_z17($z17) {
		$this->set_field_value(self::$field_z17, $z17);
	}

	public function get_m18() {
		return $this->get_field_value(self::$field_m18);
	}

	public function set_m18($m18) {
		$this->set_field_value(self::$field_m18, $m18);
	}

	public function get_z18() {
		return $this->get_field_value(self::$field_z18);
	}

	public function set_z18($z18) {
		$this->set_field_value(self::$field_z18, $z18);
	}

	public function get_m19() {
		return $this->get_field_value(self::$field_m19);
	}

	public function set_m19($m19) {
		$this->set_field_value(self::$field_m19, $m19);
	}

	public function get_z19() {
		return $this->get_field_value(self::$field_z19);
	}

	public function set_z19($z19) {
		$this->set_field_value(self::$field_z19, $z19);
	}

	public function get_m20() {
		return $this->get_field_value(self::$field_m20);
	}

	public function set_m20($m20) {
		$this->set_field_value(self::$field_m20, $m20);
	}

	public function get_z20() {
		return $this->get_field_value(self::$field_z20);
	}

	public function set_z20($z20) {
		$this->set_field_value(self::$field_z20, $z20);
	}

	public function get_m21() {
		return $this->get_field_value(self::$field_m21);
	}

	public function set_m21($m21) {
		$this->set_field_value(self::$field_m21, $m21);
	}

	public function get_z21() {
		return $this->get_field_value(self::$field_z21);
	}

	public function set_z21($z21) {
		$this->set_field_value(self::$field_z21, $z21);
	}

	public function get_m22() {
		return $this->get_field_value(self::$field_m22);
	}

	public function set_m22($m22) {
		$this->set_field_value(self::$field_m22, $m22);
	}

	public function get_z22() {
		return $this->get_field_value(self::$field_z22);
	}

	public function set_z22($z22) {
		$this->set_field_value(self::$field_z22, $z22);
	}

	public function get_m23() {
		return $this->get_field_value(self::$field_m23);
	}

	public function set_m23($m23) {
		$this->set_field_value(self::$field_m23, $m23);
	}

	public function get_z23() {
		return $this->get_field_value(self::$field_z23);
	}

	public function set_z23($z23) {
		$this->set_field_value(self::$field_z23, $z23);
	}

	public function get_m24() {
		return $this->get_field_value(self::$field_m24);
	}

	public function set_m24($m24) {
		$this->set_field_value(self::$field_m24, $m24);
	}

	public function get_z24() {
		return $this->get_field_value(self::$field_z24);
	}

	public function set_z24($z24) {
		$this->set_field_value(self::$field_z24, $z24);
	}

	public function get_m25() {
		return $this->get_field_value(self::$field_m25);
	}

	public function set_m25($m25) {
		$this->set_field_value(self::$field_m25, $m25);
	}

	public function get_z25() {
		return $this->get_field_value(self::$field_z25);
	}

	public function set_z25($z25) {
		$this->set_field_value(self::$field_z25, $z25);
	}

	public function get_m26() {
		return $this->get_field_value(self::$field_m26);
	}

	public function set_m26($m26) {
		$this->set_field_value(self::$field_m26, $m26);
	}

	public function get_z26() {
		return $this->get_field_value(self::$field_z26);
	}

	public function set_z26($z26) {
		$this->set_field_value(self::$field_z26, $z26);
	}

	public function get_m27() {
		return $this->get_field_value(self::$field_m27);
	}

	public function set_m27($m27) {
		$this->set_field_value(self::$field_m27, $m27);
	}

	public function get_z27() {
		return $this->get_field_value(self::$field_z27);
	}

	public function set_z27($z27) {
		$this->set_field_value(self::$field_z27, $z27);
	}

	public function get_m28() {
		return $this->get_field_value(self::$field_m28);
	}

	public function set_m28($m28) {
		$this->set_field_value(self::$field_m28, $m28);
	}

	public function get_z28() {
		return $this->get_field_value(self::$field_z28);
	}

	public function set_z28($z28) {
		$this->set_field_value(self::$field_z28, $z28);
	}

	public function get_m29() {
		return $this->get_field_value(self::$field_m29);
	}

	public function set_m29($m29) {
		$this->set_field_value(self::$field_m29, $m29);
	}

	public function get_z29() {
		return $this->get_field_value(self::$field_z29);
	}

	public function set_z29($z29) {
		$this->set_field_value(self::$field_z29, $z29);
	}

	public function get_m30() {
		return $this->get_field_value(self::$field_m30);
	}

	public function set_m30($m30) {
		$this->set_field_value(self::$field_m30, $m30);
	}

	public function get_z30() {
		return $this->get_field_value(self::$field_z30);
	}

	public function set_z30($z30) {
		$this->set_field_value(self::$field_z30, $z30);
	}

	public function get_m31() {
		return $this->get_field_value(self::$field_m31);
	}

	public function set_m31($m31) {
		$this->set_field_value(self::$field_m31, $m31);
	}

	public function get_z31() {
		return $this->get_field_value(self::$field_z31);
	}

	public function set_z31($z31) {
		$this->set_field_value(self::$field_z31, $z31);
	}

	public function get_es32() {
		return $this->get_field_value(self::$field_es32);
	}

	public function set_es32($es32) {
		$this->set_field_value(self::$field_es32, $es32);
	}

	public function get_ext() {
		return $this->get_field_value(self::$field_ext);
	}

	public function set_ext($ext) {
		$this->set_field_value(self::$field_ext, $ext);
	}

	public function to_array(array $options = array(), callable $func = NULL) {
		$arr = parent::to_array($options, $func);
		//unset($arr[self::$field_dy['name']]);
		//unset($arr[self::$field_userid['name']]);
		//unset($arr[self::$field_dept_id['name']]);
		//unset($arr[self::$field_m1['name']]);
		//unset($arr[self::$field_z1['name']]);
		//unset($arr[self::$field_m2['name']]);
		//unset($arr[self::$field_z2['name']]);
		//unset($arr[self::$field_m3['name']]);
		//unset($arr[self::$field_z3['name']]);
		//unset($arr[self::$field_m4['name']]);
		//unset($arr[self::$field_z4['name']]);
		//unset($arr[self::$field_m5['name']]);
		//unset($arr[self::$field_z5['name']]);
		//unset($arr[self::$field_m6['name']]);
		//unset($arr[self::$field_z6['name']]);
		//unset($arr[self::$field_m7['name']]);
		//unset($arr[self::$field_z7['name']]);
		//unset($arr[self::$field_m8['name']]);
		//unset($arr[self::$field_z8['name']]);
		//unset($arr[self::$field_m9['name']]);
		//unset($arr[self::$field_z9['name']]);
		//unset($arr[self::$field_m10['name']]);
		//unset($arr[self::$field_z10['name']]);
		//unset($arr[self::$field_m11['name']]);
		//unset($arr[self::$field_z11['name']]);
		//unset($arr[self::$field_m12['name']]);
		//unset($arr[self::$field_z12['name']]);
		//unset($arr[self::$field_m13['name']]);
		//unset($arr[self::$field_z13['name']]);
		//unset($arr[self::$field_m14['name']]);
		//unset($arr[self::$field_z14['name']]);
		//unset($arr[self::$field_m15['name']]);
		//unset($arr[self::$field_z15['name']]);
		//unset($arr[self::$field_m16['name']]);
		//unset($arr[self::$field_z16['name']]);
		//unset($arr[self::$field_m17['name']]);
		//unset($arr[self::$field_z17['name']]);
		//unset($arr[self::$field_m18['name']]);
		//unset($arr[self::$field_z18['name']]);
		//unset($arr[self::$field_m19['name']]);
		//unset($arr[self::$field_z19['name']]);
		//unset($arr[self::$field_m20['name']]);
		//unset($arr[self::$field_z20['name']]);
		//unset($arr[self::$field_m21['name']]);
		//unset($arr[self::$field_z21['name']]);
		//unset($arr[self::$field_m22['name']]);
		//unset($arr[self::$field_z22['name']]);
		//unset($arr[self::$field_m23['name']]);
		//unset($arr[self::$field_z23['name']]);
		//unset($arr[self::$field_m24['name']]);
		//unset($arr[self::$field_z24['name']]);
		//unset($arr[self::$field_m25['name']]);
		//unset($arr[self::$field_z25['name']]);
		//unset($arr[self::$field_m26['name']]);
		//unset($arr[self::$field_z26['name']]);
		//unset($arr[self::$field_m27['name']]);
		//unset($arr[self::$field_z27['name']]);
		//unset($arr[self::$field_m28['name']]);
		//unset($arr[self::$field_z28['name']]);
		//unset($arr[self::$field_m29['name']]);
		//unset($arr[self::$field_z29['name']]);
		//unset($arr[self::$field_m30['name']]);
		//unset($arr[self::$field_z30['name']]);
		//unset($arr[self::$field_m31['name']]);
		//unset($arr[self::$field_z31['name']]);
		//unset($arr[self::$field_es32['name']]);
		//unset($arr[self::$field_ext['name']]);
		return $arr;
	}

}

v_userchecklist::init_schema();
