<?php

/**
 * 权限列表/设置
 *
 * @author ChenHao
 * @copyright 2015 星密码
 * @version 2015/2/12
 */
use Models\Base\SqlOperator;
use Models\M_Dept;
use Models\M_Role;
use Models\M_User;

require '../../application.php';
require '../../loader-api.php';

check_login();

execute_request(HttpRequestMethod::Get, function() {

    $action = request_action();
    $dept1_id = request_int('dept1_id');
//    $dept2_id = request_int('dept2_id');
    $role_id = request_int('role_id');

    if ($action == 1) {
        $menu_ids = array();
        $ctrl_ids = array();
        if ($role_id > 0) {
            $role = new M_Role($role_id);
            $db = create_pdo();
            $result = $role->load($db, $role);
            if (!$result[0]) die_error(USER_ERROR, '获取职位信息失败');
            if (strlen($role->get_permit()) <= 0 || str_equals($role->get_permit(), ';')) {
                $dept = new M_Dept($role->get_dept1_id());
                $result = $dept->load($db, $dept);
                if (!$result[0]) die_error(USER_ERROR, '获取部门信息失败');
                $permit = explode(';', $dept->get_permit());
                if (isset($permit[0])) $menu_ids = array_merge($menu_ids, explode(',', $permit[0]));
                if (isset($permit[1])) $ctrl_ids = array_merge($ctrl_ids, explode(',', $permit[1]));
            }else {
                $permit = explode(';', $role->get_permit());
                if (isset($permit[0])) $menu_ids = array_merge($menu_ids, explode(',', $permit[0]));
                if (isset($permit[1])) $ctrl_ids = array_merge($ctrl_ids, explode(',', $permit[1]));
            }
        }
        if ($dept1_id > 0) {
            $dept = new M_Dept($dept1_id);
            $db = create_pdo();
            $result = $dept->load($db, $dept);
            if (!$result[0]) die_error(USER_ERROR, '获取部门信息失败');
            $permit = explode(';', $dept->get_permit());
            if (isset($permit[0])) $menu_ids = array_merge($menu_ids, explode(',', $permit[0]));
            if (isset($permit[1])) $ctrl_ids = array_merge($ctrl_ids, explode(',', $permit[1]));
        }

        $result = get_menus();
        $ctrls = get_ctrls();

        $menus = array_filter($result, function($item) {
            return in_array($item['parent_id'], array(1, 2, 3));
        });
        array_walk($menus, function(&$menu) use($result, $ctrls, $menu_ids, $ctrl_ids) {
            $childs = array_filter($result, function($item) use($menu) {
                return $item['parent_id'] == $menu['id'];
            });
            array_walk($childs, function(&$child) use($ctrls, $menu_ids, $ctrl_ids) {
                $child_ctrls = array_filter($ctrls, function($ctrl) use($child) {
                    return $ctrl['menu_id'] == $child['id'];
                });
                array_walk($child_ctrls, function(&$child_ctrl) use($ctrl_ids) {
                    $child_ctrl['state']['selected'] = in_array($child_ctrl['id'], $ctrl_ids);
                });
                $child['children'] = array_values($child_ctrls);
                $child['state']['selected'] = in_array($child['id'], $menu_ids);
            });
            $menu['children'] = array_values($childs);
            $menu['state']['selected'] = in_array($menu['id'], $menu_ids);
        });

        echo_result(array_values($menus));
    }

    if ($action == 3) {
        $userid = request_int("userId");
        $user = new M_User($userid);
        $db = create_pdo();
        $result = $user->load($db, $user);
        if (!$result[0]) die_error(USER_ERROR, '获取用户信息失败');
        $permits = $user->get_permit();
        $menus_ = get_menus();
        $ctrls = get_ctrls();
        $menu_ids = array();
        $ctrl_ids = array();
        if (strlen($permits) > 0 && !str_equals($permits, ';')) {
            $result = explode(';', $permits);
            if (isset($result[0])) $menu_ids = array_merge($menu_ids, explode(',', $result[0]));
            if (isset($result[1])) $ctrl_ids = array_merge($ctrl_ids, explode(',', $result[1]));
        } else {
            $role_id = $user->get_role_id();
            $role = new M_Role($role_id);
            $result = $role->load($db, $role);
            if (!$result[0]) die_error(USER_ERROR, '获取角色信息失败');
            $permits = $role->get_permit();
            if (strlen($permits) > 0 && !str_equals($permits, ';')) {
                $result = explode(';', $permits);
                if (isset($result[0])) $menu_ids = array_merge($menu_ids, explode(',', $result[0]));
                if (isset($result[1])) $ctrl_ids = array_merge($ctrl_ids, explode(',', $result[1]));
            } else {
                $deptid = $user->get_dept1_id();
                $dept = new M_Dept($deptid);
                $result = $dept->load($db, $dept);
                if (!$result[0]) die_error(USER_ERROR, '获取部门信息失败');
                $permits = $dept->get_permit();
                if (strlen($permits) > 0 && !str_equals($permits, ';')) {
                    $result = explode(';', $permits);
                    if (isset($result[0])) $menu_ids = array_merge($menu_ids, explode(',', $result[0]));
                    if (isset($result[1])) $ctrl_ids = array_merge($ctrl_ids, explode(',', $result[1]));
                }
            }
        }
        $menus = array_filter($menus_, function($item) {
            return in_array($item['parent_id'], array(1, 2, 3));
        });
        array_walk($menus, function(&$menu) use($menus_, $ctrls, $menu_ids, $ctrl_ids) {
            $childs = array_filter($menus_, function($item) use($menu) {
                return $item['parent_id'] == $menu['id'];
            });
            array_walk($childs, function(&$child) use($ctrls, $menu_ids, $ctrl_ids) {
                $child_ctrls = array_filter($ctrls, function($ctrl) use($child) {
                    return $ctrl['menu_id'] == $child['id'];
                });
                array_walk($child_ctrls, function(&$child_ctrl) use($ctrl_ids) {
                    $child_ctrl['state']['selected'] = in_array($child_ctrl['id'], $ctrl_ids);
                });
                $child['children'] = array_values($child_ctrls);
                $child['state']['selected'] = in_array($child['id'], $menu_ids);
            });
            $menu['children'] = array_values($childs);
            $menu['state']['selected'] = in_array($menu['id'], $menu_ids);
        });
        echo_result(array('code' => 0, 'list' => array_values($menus)));
    }
});

execute_request(HttpRequestMethod::Post, function() {
    $action = request_action();
    $dept_id = request_int('dept_id');
    $role_ids = request_string('role_ids');
    $menu_ids = request_string('menu_ids');
    $ctrl_ids = request_string('ctrl_ids');
    $user_ids = request_string('user_ids');

    if ($action == 1) {
        $permit = $menu_ids . ';' . $ctrl_ids;
        $db = create_pdo();
        pdo_transaction($db, function($db) use($dept_id, $role_ids, $permit) {
            if ($dept_id > 0) {
                $dept = new M_Dept($dept_id);
                $dept->set_permit($permit);
                $result = $dept->update($db, true);
                if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存部门权限失败', $result);
            }
            if (isset($role_ids)) {
                $role_ids = explode(',', $role_ids);
                if (!empty($role_ids)) {
                    $role = new M_Role();
                    $role->set_permit($permit);
                    $role->set_where_and(M_Role::$field_id, SqlOperator::In, $role_ids);
                    $result = $role->update($db, true);
                    if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存职位权限失败', $result);
                }
            }
        });
        echo_msg('保存成功');
    }

    if ($action == 2) {
        $permit = $menu_ids . ';' . $ctrl_ids;
        $db = create_pdo();
        pdo_transaction($db, function($db) use($user_ids, $permit) {
            if (isset($user_ids)) {
                $user_ids = explode(',', $user_ids);
                if (!empty($user_ids)) {
                    $user = new M_User();
                    $user->set_permit($permit);
                    $user->set_where_and(M_User::$field_userid, SqlOperator::In, $user_ids);
                    $result = $user->update($db, true);
                    if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存用户权限失败', $result);
                }
            }
        });
        echo_msg('保存用户权限成功~');
    }
});
