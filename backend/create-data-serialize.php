<?php

//废弃不用
die;

use Models\Base\Model;
use Models\S_Menu;
use Models\M_Dept;
use Models\M_User;

require '../application.php';
require '../loader-api.php';

$key = request_md5_32('key');
if (strcmp($key, BACKEND_KEY) !== 0) die_error(USER_ERROR, '密钥错误');

header("Content-Type:text/html; charset:utf-8");
$document_root = $_SERVER['DOCUMENT_ROOT'];

$db = create_pdo();

$menu = new S_Menu();
$result = Model::query_list($db, $menu);
if (!$result[0]) die_error(USER_ERROR, '读取菜单失败，请刷新重试');
$result = Model::list_to_array($result['models']);
$file_path = $document_root . '/Data/serialized-menus.cache';
$length = file_put_contents($file_path, serialize($result));
$status = $length === false ? 'failed' : 'successed';
echo 'Build "' . $file_path . '" ' . $status . '<br>';

$dept = new M_Dept();
$result = Model::query_list($db, $dept);
if (!$result[0]) die_error(USER_ERROR, '读取部门信息失败，请刷新重试');
$result = Model::list_to_array($result['models'], array(), function(&$d) {
            $d['text'] = $d['name'];
            $d['icon'] = $d['class'];
            //unset($d['name']);
            //unset($d['class']);
        });
$file_path = $document_root . '/Data/serialized-depts.cache';
$length = file_put_contents($file_path, serialize($result));
$status = $length === false ? 'failed' : 'successed';
echo 'Build "' . $file_path . '" ' . $status . '<br>';

$user = new M_User();
$user->set_query_fields(array(M_User::$field_userid, M_User::$field_employee_no, M_User::$field_username, M_User::$field_dept1_id, M_User::$field_dept2_id, M_User::$field_role_id));
$result = Model::query_list($db, $user);
if (!$result[0]) die_error(USER_ERROR, '读取员工信息失败，请刷新重试');
$result = Model::list_to_array($result['models'], array(), function (&$d) {
            $d['id'] = $d['userid'];
        });
$file_path = $document_root . '/Data/serialized-employees.cache';
$length = file_put_contents($file_path, serialize($result));
$status = $length === false ? 'failed' : 'successed';
echo 'Build "' . $file_path . '" ' . $status . '<br>';
