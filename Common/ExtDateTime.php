<?php

/**
 * 时间扩展类
 *
 * @author ChenHao
 * @version 2015/1/10
 */

namespace Common;

$GLOBALS['/common/ExtDateTime.php'] = 1;

use DateTime;

class ExtDateTime extends DateTime {

//    public function __construct($time = 'now', \DateTimeZone $timezone = NULL) {
//        parent::__construct($time, $timezone);
//    }

    public function setDatetime($datetime_string) {
        $this->modify($datetime_string);
        return $this;
    }

    public function addYears($year) {
        $this->modify("$year year");
        return $this;
    }

    public function addMonths($month) {
        $this->modify("$month month");
        return $this;
    }

    public function addDays($day) {
        $this->modify("$day day");
        return $this;
    }

    public function addHours($hour) {
        $this->modify("$hour hour");
        return $this;
    }

    public function addMinutes($minute) {
        $this->modify("$minute minute");
        return $this;
    }

    public function addSeconds($second) {
        $this->modify("$second second");
        return $this;
    }

    public function getYear() {
        return $this->format('Y');
    }

    public function getMonth() {
        return $this->format('m');
    }

    public function getDay() {
        return $this->format('d');
    }

    public function getHour() {
        return $this->format('H');
    }

    public function getMinute() {
        return $this->format('i');
    }

    public function getSecond() {
        return $this->format('s');
    }

    public function getTicks() {
        return $this->format('U');
    }

    public function dayOfWeek() {
        return $this->format('w');
    }

    public function formatDate($format = 'Y-m-d') {
        return $this->format($format);
    }

    public function formatDatetime($format = 'Y-m-d H:i:s') {
        return $this->format($format);
    }

}
