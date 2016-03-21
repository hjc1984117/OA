<?php

/**
 * 部门相关操作
 *
 * @author ChenHao
 * @copyright 2015 星密码
 * @version 2015/1/26
 */
use Models\Base\Model;
use Models\M_Dept;

require '../../application.php';
require '../../loader-api.php';

check_login();

$action = request_action();
execute_request(HttpRequestMethod::Post, function() use($action) {
    $deptid = request_int("deptid");
    $deptname = request_string("deptname");
    $parentid = request_int("parentid");
    filter_numeric($parentid, 0);
    $dept = new M_Dept();
    if (isset($deptid)) {
//        $dept->set_id($deptid);
//        $dept->set_text($deptname);
//        $dept->set_parent_id($parentid);
//        $db = create_pdo();
//        $result = $dept->update($db);
//        if (!$result[0]) die_error(USER_ERROR, '更新部门信息失败~');
//        echo_msg("更新部门信息成功~");
    } else {
        $db = create_pdo();
        $result = Model::execute_custom_sql($db, "select MAX(d.id) nid from M_Dept d WHERE d.parent_id = " . $parentid);
        if (!$result[0]) die_error(USER_ERROR, '添加部门信息失败,请稍后重试~');
        $newDeptId = $result['results'][0]['nid'];
        if ($parentid == 0) {
            filter_numeric($newDeptId, 1);
        } else {
            filter_numeric($newDeptId, $parentid * 100);
        }
        $dept->set_id($newDeptId + 1);
        $dept->set_text($deptname);
        $dept->set_parent_id(0);
        $db = create_pdo();
        $result = $dept->insert($db);
        if (!$result[0]) die_error(USER_ERROR, '添加部门信息失败~');
        echo_msg("加部门信息成功~");
    }
});
execute_request(HttpRequestMethod::Get, function() use($action) {
    $groupId = request_string('groupId');
    $hasZH = request_int("zh");
    $hasZHB = request_int("zhb");
    $groups = explode(',', $groupId);
    $result = get_depts();
    $depts = null;
    if (isset($groupId) && $groupId != 0) {
        $depts = array_filter($result, function($item) use ($groups) {
            return $item['parent_id'] == 0 && in_array($item['id'], $groups);
        });
    } else {
        $depts = array_filter($result, function($item) use($hasZH, $hasZHB) {
            if (isset($hasZH) && isset($hasZHB)) {
                return $item['parent_id'] == 0;
            } else {
                if (isset($hasZH)) {
                    return $item['parent_id'] == 0 && $item['id'] != 1000;
                }
                if ($hasZHB) {
                    return $item['parent_id'] == 0 && $item['id'] != 500;
                }
                return $item['parent_id'] == 0 && $item['id'] != 1000 && $item['id'] != 500;
            }
        });
    }
    array_walk($depts, function(&$dept) use($result) {
        $childs = array_filter($result, function($item) use($dept) {
            return $item['parent_id'] == $dept['id'];
        });
        $dept['children'] = array_values($childs);
    });
    echo_result(array_values($depts));
});
