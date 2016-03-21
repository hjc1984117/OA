<?php

/**
 * 高精度数字运算
 *
 * @author ChenHao
 * @version 2015/1/10
 */

namespace Common;

$GLOBALS['/common/ExtNumeric.php'] = 1;

class ExtNumeric {

    public $original_value = 0; //原始数值
    public $scale = 0; //精度（小数点位数）

    public function __construct($original_value = 0, $scale = 0) {
        $this->original_value = $original_value;
        $this->scale = $scale;
    }

    public function add($change_value) {
        $this->original_value = bcadd($this->original_value, $change_value, $this->scale);
        return $this;
    }

    public function sub($change_value) {
        $this->original_value = bcsub($this->original_value, $change_value, $this->scale);
        return $this;
    }

    public function mul($change_value) {
        $this->original_value = bcmul($this->original_value, $change_value, $this->scale);
        return $this;
    }

    public function div($change_value) {
        $this->original_value = bcdiv($this->original_value, $change_value, $this->scale);
        return $this;
    }

    public function setScale($scale = 0) {
        return $this->format($format);
    }

    public function setValue($value) {
        return $this->original_value = $value;
    }

    public function getValue() {
        return $this->original_value;
    }

}
