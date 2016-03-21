<?php

use Models\Base\Model;
use Models\S_Menu;
use Models\S_Ctrl;
use Models\M_Dept;
use Models\M_Role;
use Models\M_User;
use Models\Base\SqlOperator;

require '../application.php';
require '../loader-api.php';
require '../Common/Str2PY.php';

$key = request_md5_32('key');
if (strcmp($key, BACKEND_KEY) !== 0) die_error(USER_ERROR, '密钥错误');

header("Content-Type:text/html; charset:utf-8");
$document_root = $_SERVER['DOCUMENT_ROOT'];

$data_content.= "<?php\r\n\r\n";
$data_content.="/**\r\n* 根据数据库数据生成的Array\r\n*\r\n* @author 自动生成\r\n* @copyright (c) 2015, 星密码集团\r\n* @version " . date('Y/m/d') . "\r\n*/\r\n";
$data_content.= "\r\n\$GLOBALS['/Data/generated-datas.php'] = 1;\r\n";

$db = create_pdo();

$menu = new S_Menu();
$menu->set_where(S_Menu::$field_disabled, SqlOperator::Equals, 1);
$menu->set_order_by(S_Menu::$field_parent_id, 'ASC');
$menu->set_order_by(S_Menu::$field_sort, 'DESC');
$result = Model::query_list($db, $menu);
if (!$result[0]) die_error(USER_ERROR, '读取菜单失败，请刷新重试');
$result = Model::list_to_array($result['models']);
$data_content.=get_function_content('get_menus', $result);

$ctrl = new S_Ctrl();
$result = Model::query_list($db, $ctrl);
if (!$result[0]) die_error(USER_ERROR, '读取控件失败，请刷新重试');
$result = Model::list_to_array($result['models']);
$data_content.=get_function_content('get_ctrls', $result);

$dept = new M_Dept();
$result = Model::query_list($db, $dept);
if (!$result[0]) die_error(USER_ERROR, '读取部门信息失败，请刷新重试');
$result = Model::list_to_array($result['models']);
$data_content.=get_function_content('get_depts', $result);

$role = new M_Role();
$result = Model::query_list($db, $role);
if (!$result[0]) die_error(USER_ERROR, '读取职位信息失败，请刷新重试');
$result = Model::list_to_array($result['models']);
$data_content.=get_function_content('get_roles', $result);
$roles = array();
foreach ($result as $key => $value) {
    $roles[$value['id']] = $value;
}

$user = new M_User();
$user->set_query_fields(array(M_User::$field_userid, M_User::$field_sex, M_User::$field_employee_no, M_User::$field_username, M_User::$field_truename, M_User::$field_dept1_id, M_User::$field_dept2_id, M_User::$field_role_id, M_User::$field_join_time, M_User::$field_status, M_User::$field_permit));
$user->set_where_and(M_User::$field_status, SqlOperator::In, array(-1, 1, 2));
$result = Model::query_list($db, $user);
if (!$result[0]) die_error(USER_ERROR, '读取员工信息失败，请刷新重试');
$letter_group_map = array(
    'A-E' => array('A', 'B', 'C', 'D', 'E'),
    'F-J' => array('F', 'G', 'H', 'I', 'J'),
    'K-O' => array('K', 'L', 'M', 'N', 'O'),
    'P-T' => array('P', 'Q', 'R', 'S', 'T'),
    'U-Z' => array('U', 'V', 'W', 'X', 'Y', 'Z')
);
$managers = array();
$str2py = new Str2PY();
$result = Model::list_to_array($result['models'], array(), function (&$d) use($str2py, $letter_group_map, $roles, &$managers) {
            $d['id'] = $d['userid'];
            $d['py'] = $str2py->getInitials($d['truename']);
            foreach ($letter_group_map as $key => $value) {
                if (in_array(substr($d['py'], 0, 1), $value)) {
                    $d['py_group'] = $key;
                    break;
                }
            }
            $d['role_type'] = $roles[$d['role_id']]['role_type'];
            if ($d['role_type'] > 0) {
                $managers[] = $d;
            }
        });
$data_content.=get_function_content('get_employees', $result);

$data_content.=get_function_content('get_managers', $managers);

$file_path = $document_root . '/Data/generated-datas.php';
$length = file_put_contents($file_path, $data_content);

$status = $length === false ? 'failed' : 'successed';
//echo 'Build "' . $file_path . '" ' . $status . '<br>';
echo_result(array("code" => '0', 'states' => $status));

function get_function_content($func_name, $data) {
    $data1 = array();
    foreach ($data as $key => $value) {
        $data1[$value['id']] = $value;
    }
    $data = var_export($data1, true);
    $function_content = "\r\nfunction $func_name(){";
    //$function_content.="\r\t\$data = $data;";
    $function_content.="\r\nreturn $data;";
    $function_content.="\r\n}";
    return $function_content;
}
