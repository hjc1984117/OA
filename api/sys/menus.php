<?php

/**
 * 菜单列表
 *
 * @author ChenHao
 * @copyright 2015 星密码
 * @version 2015/1/26
 */
require '../../application.php';
require '../../loader-api.php';

check_login();

list($userid) = filter_request(array(
    request_userid()));

$user = get_employees()[$userid];
$dept1_id = $user['dept1_id'];
$dept2_id = $user['dept2_id'];
$role_id = $user['role_id'];

if (strlen($user['permit']) > 0 && !str_equals($user['permit'], ';')) {
    $permit = $user['permit'];
} else {
    $role = get_roles()[$role_id];
    if (strlen($role['permit']) > 0 && !str_equals($role['permit'], ';')) {
        $permit = $role['permit'];
    } else {
        $dept1 = get_depts()[$dept1_id];
        $permit = $dept1['permit'];
    }
}

$permits = explode(';', $permit);
$user_menus = array_merge(array(), explode(',', $permits[0]));
$user_ctrls = array_merge(array(), explode(',', $permits[1]));

//$menu = new S_Menu();
//$db = create_pdo();
//$result = Model::query_list($db, $menu);
//if (!$result[0]) die_error(USER_ERROR, '读取菜单失败，请刷新重试');
//$result = Model::list_to_array($result['models']);

$result = get_menus();

$menus = array_filter($result, function($item) {
    return !in_array($item['id'], array(1, 2, 3));
});
$menus = array_filter($menus, function($item) use($user_menus) {
    return in_array($item['id'], $user_menus);
});

$user_menus = $menus;

$menus = array_filter($menus, function($item) {
    return in_array($item['parent_id'], array(1, 2, 3));
});

array_walk($menus, function(&$menu) use($user_menus) {
    $childs = array_filter($user_menus, function($item) use($menu) {
        return $item['parent_id'] == $menu['id'];
    });
    $menu['childs'] = array_values($childs);
});
echo_result(array('permitMenus' => array_values($menus), 'permitButtons' => $permits[1]));
