<?php

/**
 * 员工扩展类
 *
 * @author ChenHao
 * @copyright (c) 2015, 星密码集团
 * @version 1.0
 */

namespace Models;

$GLOBALS['/Models/M_UserExt.php'] = 1;

class M_UserExt extends M_User {

    public function to_array(array $options = array(), callable $func = NULL) {
        $arr = parent::to_array($options, $func);
        $arr['sex'] = (string) $arr['sex'];
        $arr['status'] = (string) $arr['status'];
        return $arr;
    }

}
