<?php

/**
 * 岗位相关操作
 *
 * @author ChenHao
 * @copyright 2015 星密码
 * @version 2015/1/26
 */
use Models\Base\Model;
use Models\M_Role;

require '../../application.php';
require '../../loader-api.php';

check_login();

$action = request_action();
execute_request(HttpRequestMethod::Post, function() use($action) {
    $dept1_id = request_int("dept1_id");
//    $dept2_id = request_int("dept2_id");
    $role_id = request_int("role_id");
    $role_text = request_string("role_text");
    $shares = request_float("shares");
    if (isset($role_id)) {
//        $role = new M_Role($role_id);
//        $role->set_text($role_text);
//        $role->set_dept1_id($dept1_id);
//        $role->set_dept2_id($dept2_id);
//        $db = create_pdo();
//        $result = $role->update($db);
//        if (!$result[0]) die_error(USER_ERROR, '更新岗位信息失败~');
//        echo_msg("更新岗位信息成功~");
    } else {
        $role = new M_Role();
        $db = create_pdo();
        $result = Model::execute_custom_sql($db, "select MAX(r.id) nid from m_role r where r.dept1_id = " . $dept1_id);
        if (!$result[0]) die_error(USER_ERROR, '添加岗位信息失败,请稍后重试~');
        $newRoleId = $result['results'][0]['nid'];
        filter_numeric($newRoleId, $dept1_id * 100);
        $role->set_id($newRoleId + 1);
        $role->set_text($role_text);
        $role->set_dept1_id($dept1_id);
        $role->set_dept2_id(0);
        $role->set_shares($shares);
        $db = create_pdo();
        $result = $role->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加岗位信息失败~');
        echo_msg("添加岗位信息成功~");
    }
});
