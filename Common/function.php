<?php

/**
 * 公共函数库(业务逻辑相关)
 *
 * @author ChenHao
 * @version 2015/1/10
 */
$GLOBALS['/common/function.php'] = 1;

use Models\Base\Model;
use Models\Base\SqlOperator;
use Models\Base\ModelToArrayOptions;
use Models\M_UserToken;
use Models\S_WorkflowLog;
use Models\S_DataChangeLog;
use Common\RoleType;

require_once('Mail/PHPMailerAutoload.php');

/**
 * 通用的参数签名校验
 * @param type $additional_sign_content 追加需要加入签名的字符串
 * @param type $check_time_diff 是否验证时差，默认false，如果验证，最大时间误差为5分钟
 */
function abs_check_sign($additional_sign_content = '', $check_time_diff = false) {
    list($action, $time, $sign) = filter_request(array(
        request_action(),
        request_datetime('time'),
        request_md5_32('sign')));
    $sign_str = $action . $time . $additional_sign_content . PUBLIC_KEY;
    if (!str_equals($sign, md5($sign_str))) die_error(USER_ERROR, 'Access forbidden!');
    if ($check_time_diff && abs(time() - strtotime($time)) > 300) die_error(USER_ERROR, 'Access forbidden!');
}

function build_token($userid, $password) {
    return md5($userid . $password . PRIVATE_KEY);
}

function is_login($db) {
    return check_token($db, true);
}

function check_login($return_value = false, $redirect_url = null) {
    if (is_null(request_user()) || request_user()['userid'] <= 0 || !is_md5_32(request_user()['token'])) {
        if ($return_value) return false;
        if (isset($redirect_url)) redirect_302($redirect_url);
        die_error(USER_LOGIN_EXPIRED, '您尚未登录，无权进行此操作');
    }
    if ($return_value) return true;
}

function check_token($db, $return_value = false, $redirect_url = null) {
    $VSERSION = request_string("__VSERSION__");
    $Z = request_string("z");
    if (isset($Z)) {
        $attdata = strtoupper(md5("283F42764DA6DBA2522412916B031080attdata" . date("Ymd")));
        if (!str_equals($attdata, $Z)) {
            echo '-999';
            exit();
        } else {
            return true;
        }
    }
//    if (!isset($VSERSION) || !str_equals($VSERSION, PAGE_VERSION)) die_error(PAGE_VERSION_ERROR, '当前页面版本与服务器不匹配,请强制刷新浏览器缓存后重试~');
    check_login($return_value, $redirect_url);
    $userToken = new M_UserToken(request_user()['userid']);
    $userToken->set_query_fields(array(M_UserToken::$field_session_magic_mark, M_UserToken::$field_status));
    $userToken->set_where_and(M_UserToken::$field_token, SqlOperator::Equals, request_user()['token']);
    if (!isset($db)) $db = create_pdo();
    $result = $userToken->load($db, $userToken);
    if (!$result[0]) {
        setcookie('YCOA_USER', null, time() - 3600);
        setcookie('YCOA_SESSION_KEY', null, time() - 3600);
        if ($return_value) return false;
        if (isset($redirect_url)) redirect_302($redirect_url);
        die_error(USER_LOGIN_EXPIRED, '用户未登录或令牌错误');
    }
    if (!str_equals($userToken->get_session_magic_mark(), request_user()['session_magic_mark'])) {
        setcookie('YCOA_USER', null, time() - 3600);
        setcookie('YCOA_SESSION_KEY', null, time() - 3600);
        if ($return_value) return false;
        if (isset($redirect_url)) redirect_302($redirect_url);
        die_error(USER_LOGIN_EXPIRED, '当前登录已过期，请重新登录！');
    }
    if ($userToken->get_status() == 3) {
        if ($return_value) return false;
        if (isset($redirect_url)) redirect_302($redirect_url);
        die_error(USER_ERROR, '当前用户已被禁用');
    }
    if ($return_value) return true;
}

function set_cookie($db, M_UserToken $userToken, $remember = false) {
    //$session_magic_mark = md5(randomGUID() . $userToken->get_userid() . $userToken->get_token() . PRIVATE_KEY);
    $session_magic_mark = md5($userToken->get_userid() . $userToken->get_token() . PRIVATE_KEY);
    $userToken1 = new M_UserToken($userToken->get_userid());
    $userToken1->set_session_magic_mark($session_magic_mark);
    if (!isset($db)) $db = create_pdo();
    //$result = $userToken1->update($db);
    $result = $userToken1->update($db, true);
    if (!$result[0]) die_error(USER_ERROR, '更新用户令牌失败');
    $user = get_employees()[$userToken->get_userid()];
    $dept = get_depts()[$user['dept1_id']];
    $expire_time = $remember ? time() + 3600 * SESSION_EXPIRE_HOUR : null;
    $user_cookie = array('userid' => $userToken->get_userid(), 'username' => $userToken->get_username(), 'avatar' => $userToken->get_avatar());
    if (isset($user)) {
        $user_cookie = array_merge($user_cookie, array('employee_no' => $user['employee_no'], 'role_id' => $user['role_id'], 'role_type' => $user['role_type']));
    }
    if (isset($dept)) {
        $user_cookie = array_merge($user_cookie, array('dept1_id' => $dept['id'], 'dept1_name' => $dept['text']));
    }
    setcookie('YCOA_USER', base64_encode(json_encode($user_cookie)), $expire_time, '/');
    $session_key = array('userid' => $userToken->get_userid(), 'username' => $userToken->get_username(), 'token' => $userToken->get_token(), 'session_magic_mark' => $session_magic_mark);
    setcookie('YCOA_SESSION_KEY', authcode(json_encode($session_key), 'ENCODE', PRIVATE_KEY, 3600 * SESSION_EXPIRE_HOUR), $expire_time, '/');
}

function id_2_text(&$d) {
    if (isset($d['dept1_id']) && $d['dept1_id'] > 0) {
        $depts = get_depts();
        $d['dept1_text'] = $depts[$d['dept1_id']]['text'];
        $d['dept2_id'] = $d['dept1_id'];
    } else {
        $d['dept1_text'] = '综合部';
    }
    if (isset($d['dept2_id']) && $d['dept2_id'] > 0) {
        $depts = get_depts();
        $d['dept2_text'] = $depts[$d['dept2_id']]['text'];
    } else {
        $d['dept2_text'] = '综合部';
    }
    if (isset($d['role_id']) && $d['role_id'] > 0) {
        $roles = get_roles();
        $d['role_text'] = $roles[$d['role_id']]['text'];
    }
    if (isset($d['sex'])) {
        $d['sex_text'] = $d['sex'] == 1 ? '男' : '女';
    }
}

function id_2_text_4_employee(&$v, $k) {
    switch ($k) {
        case 'dept1_id':
            $v = get_depts()[$v]['text'];
            break;
        case 'role_id':
            $v = get_roles()[$v]['text'];
            break;
        case 'sex':
            $v = get_sex_text_mapping()[$v];
            break;
        case 'status':
            $v = get_employee_status_mapping()[$v];
            break;
        default:
            break;
    }
}

function id_2_text_4_salecount(&$v, $k) {
    id_2_text_4_employee($v, $k);
    switch ($k) {
        case 'isTimely':
        case 'isQQTeach':
            $v = get_bool_text_mapping()[$v];
            break;
        case 'status':
            break;
        default:
            break;
    }
}

//根据当前登录用户职位筛选记录
function get_record_by_role(Model &$model) {
    list($userid) = filter_request(array(request_login_userid()));
    $user = get_employees()[$userid];
    $role_id = $user['role_id'];
    $role = get_roles()[$role_id];
    if (!in_array($role_id, array(0, 203, 208, 210, 212, 215))) {
        if ($role_id == 1301) {
            $model->set_where_and($model->get_field_by_name('dept1_id'), SqlOperator::In, array(5, 13));
        }
        //2015-10-23 新加,软件部经理 审核 话费部请假 begin
        else if ($role_id == 1101) {
            $model->set_where_and($model->get_field_by_name('dept1_id'), SqlOperator::In, array(11, 14));
        }
        //2015-10-23 新加,软件部经理 审核 话费部请假 end
        else {
            if ($role_id == -1 || $role['role_type'] == 0) {
                if ($model->get_field_by_name('userid')) {
                    $model->set_where_and($model->get_field_by_name('userid'), SqlOperator::Equals, $userid);
                } else {
                    $model->set_where_and($model->get_field_by_name('addUserId'), SqlOperator::Equals, $userid);
                }
            }
            if (in_array($role['role_type'], array(5, 6, 9, 10, 11, 12, 13))) {
                $model->set_where_and($model->get_field_by_name('dept1_id'), SqlOperator::Equals, $user['dept1_id']);
            }
        }
    }
}

function get_role_id($userid = null) {
    if (!isset($userid)) $userid = request_userid();
    $user = get_employees()[$userid];
    return $user['role_id'];
}

function get_dept_id($userid = null) {
    if (!isset($userid)) $userid = request_userid();
    $user = get_employees()[$userid];
    return $user['dept1_id'];
}

function get_role_type($userid = null) {
    if (!isset($userid)) $userid = request_userid();
    $user = get_employees()[$userid];
    $role_id = $user['role_id'];
    $role = get_roles()[$role_id];
    $role_type_configs = array(
        0 => RoleType::Employee,
        1 => RoleType::CompanyManager0,
        2 => RoleType::CompanyManager1,
        3 => RoleType::CompanyManager2,
        4 => RoleType::HrManager,
        5 => RoleType::FinancialManager,
        6 => RoleType::DeptManager,
        7 => RoleType::HrManager1,
        8 => RoleType::HrManager2,
        9 => RoleType::FinancialManager1,
        10 => RoleType::FinancialManager2,
        11 => RoleType::DeptManager1,
        12 => RoleType::DeptManager2,
        13 => RoleType::DeptManager3
    );
    $role_type = $role_type_configs[$role['role_type']];
    return $role_type;
}

function get_role_type_name($role_type_id, $name_type = 'name_en') {
//    const Employee = 'Employee'; //0.普通员工
//    const CompanyManager0 = 'CompanyManager0'; //1.董事长
//    const CompanyManager1 = 'CompanyManager1'; //2.总经理
//    const CompanyManager2 = 'CompanyManager2'; //3.副总经理
//    const HrManager = 'HrManager'; //4.人事经理
//    const FinancialManager = 'FinancialManager'; //5.财务经理
//    const DeptManager = 'DeptManager'; //6.部门经理
    $role_type_configs = array(
        0 => array('name_en' => RoleType::Employee, 'name_cn' => '普通员工'),
        1 => array('name_en' => RoleType::CompanyManager0, 'name_cn' => '董事长'),
        2 => array('name_en' => RoleType::CompanyManager1, 'name_cn' => '总经理'),
        3 => array('name_en' => RoleType::CompanyManager2, 'name_cn' => '副总经理'),
        4 => array('name_en' => RoleType::HrManager, 'name_cn' => '行政'),
        5 => array('name_en' => RoleType::FinancialManager, 'name_cn' => '财务'),
        6 => array('name_en' => RoleType::DeptManager, 'name_cn' => '部门经理'),
        7 => array('name_en' => RoleType::HrManager1, 'name_cn' => '行政专员'),
        8 => array('name_en' => RoleType::HrManager2, 'name_cn' => '行政专员'),
        9 => array('name_en' => RoleType::FinancialManager1, 'name_cn' => '财务专员'),
        10 => array('name_en' => RoleType::FinancialManager2, 'name_cn' => '财务专员'),
        11 => array('name_en' => RoleType::DeptManager1, 'name_cn' => '部门主管'),
        12 => array('name_en' => RoleType::DeptManager2, 'name_cn' => '部门副主管'),
        13 => array('name_en' => RoleType::DeptManager3, 'name_cn' => '部门副主管')
    );
    return $role_type_configs[$role_type_id][$name_type];
}

function get_role_name_by_id($role_id) {
    $role_name = get_roles()[$role_id]['text'];
    return $role_name;
}

//添加数据变更记录通用方法
//type记录类型:{1:员工档案(U_User),2:星密码销售统计(P_Salecount),3:星密码补欠款(P_Fills),4:星密码升级(P_Upgrade),5:星密码退款(P_Refund),
//6:软件部销售统计(P_Salecount_soft),7:软件部补欠款(P_Fills_soft),8:软件部升级(P_Upgrade_soft),9:软件部退款(P_Refund_soft),10:星密码QQ接入(P_QQAccess)}
function add_data_add_log($db, $request_data, Model $model, $type) {
    $request_data = (array) $request_data;
    $data_change_log = new S_DataChangeLog();
    $data_change_log->set_obj_id($model->get_id());
    $data_change_log->set_type($type);
    $data_change_log->set_addtime('now');
    $data_change_log->set_userid(request_login_userid());
    $data_change_log->set_username(request_login_username());
    $user = get_employees()[request_login_userid()];
    $role_id = $user['role_id'];
    $role_type = get_roles()[$role_id]['role_type'];
    $data_change_log->set_role_type($role_type);
    $data_change_log->set_role_id($role_id);
    $data_change_log->set_comment($request_data['comment']);
    $data_change_log->set_changed_desc('添加记录');
    if (!isset($db)) $db = create_pdo();
    $result = $data_change_log->insert($db);
    //if (!$result[0]) die_error(USER_ERROR, '添加数据变更记录失败，请稍候重试~');
}

//添加数据变更记录通用方法
//type记录类型:{1:员工档案(U_User),2:星密码销售统计(P_Salecount),3:星密码补欠款(P_Fills),4:星密码升级(P_Upgrade),5:星密码退款(P_Refund),
//6:软件部销售统计(P_Salecount_soft),7:软件部补欠款(P_Fills_soft),8:软件部升级(P_Upgrade_soft),9:软件部退款(P_Refund_soft),10:星密码QQ接入(P_QQAccess)}
function add_data_change_log($db, $request_data, Model $model, $type, $msg = "") {
    $request_data = (array) $request_data;
    if (array_key_exists('editorValue', $request_data)) {
        $request_data['attachment'] = $request_data['editorValue'];
    }
    $new_data = array();
    $query_fields = array();
    foreach ($request_data as $key => $value) {
        $field = $model->get_field_by_name($key);
        if (isset($field)) {
            $query_fields[] = $field;
            $new_data[$key] = internal_conv_value($field['type'], $value);
        }
    }
    $model->set_query_fields($query_fields);
    if (!isset($db)) $db = create_pdo();
    $result = $model->load($db, $model);
    if (!$result[0]) return; //die_error(USER_ERROR, '查询原始数据失败，请稍候重试~');
    $old_data = $model->to_array();
    $data_change_log = new S_DataChangeLog();
    $data_change_log->set_obj_id($model->get_id());
    $data_change_log->set_type($type);
    $data_change_log->set_addtime('now');
    $data_change_log->set_userid(request_login_userid());
    $data_change_log->set_username(request_login_username());
    $user = get_employees()[request_login_userid()];
    $role_id = $user['role_id'];
    $role_type = get_roles()[$role_id]['role_type'];
    $data_change_log->set_role_type($role_type);
    $data_change_log->set_role_id($role_id);
    $data_change_log->set_comment($request_data['comment']);
    ksort($old_data);
    ksort($new_data);
    $data_change_log->set_old_data(json_encode($old_data));
    $data_change_log->set_new_data(json_encode($new_data));
    $type_callable_map = array(
        1 => 'id_2_text_4_employee',
        2 => 'id_2_text_4_salecount'
    );
    array_walk($old_data, $type_callable_map[$type]);
    array_walk($new_data, $type_callable_map[$type]);
    $changed_desc = '';
    if (str_equals($msg, "")) {
        $field_key_names = (array) $request_data['key_names'];
        foreach ($new_data as $key => $new_value) {
            $old_value = $old_data[$key];
            if (!str_equals($new_value, $old_value)) {
                $changed_field_name = isset($field_key_names[$key]) ? $field_key_names[$key] : $key;
                $changed_desc.='[' . $changed_field_name . ']从"' . $old_value . '"变更为"' . $new_value . '";';
            }
        }
        if (strlen($changed_desc) <= 0) $changed_desc = '编辑未做任何修改';
    } else {
        $changed_desc = $msg;
    }
    $data_change_log->set_changed_desc($changed_desc);
    if (!isset($db)) $db = create_pdo();
    $result = $data_change_log->insert($db);
    //if (!$result[0]) die_error(USER_ERROR, '添加数据变更记录失败，请稍候重试~');
}

//更改流程状态公共方法
function set_workflow_status($db, $request_data, array $workflow_configs, Model &$model) {
    $request_data->previous_status = $request_data->status;
    set_workflow_status_inner_func($request_data, $workflow_configs, $model);
    add_workflow_log($db, $request_data, $workflow_configs, $model);
}

//添加流程流转记录通用方法
function add_workflow_log($db, $request_data, array $workflow_configs, Model $model) {
//流程记录
//记录类型:{1:行政处罚,2:调休,3:请假,4:补卡(补签)} 
    $workflow_log = new S_WorkflowLog();
    $workflow_log->set_workflow_id($model->get_id());
    $workflow_log->set_type(get_workflow_type_by_name($workflow_configs['name']));
    $workflow_log->set_addtime('now');
    $workflow_log->set_userid(request_login_userid());
    $workflow_log->set_username(request_login_username());
    $user = get_employees()[request_login_userid()];
    $role_id = $user['role_id'];
    $role_type = get_roles()[$role_id]['role_type'];
    $workflow_log->set_role_type($role_type);
    $workflow_log->set_role_id($role_id);
    $workflow_log->set_workflow_status($model->get_status());
    $workflow_log->set_comment($request_data->comment);
    if (!isset($db)) $db = create_pdo();
    $result = $workflow_log->insert($db);
    //if (!$result[0]) die_error(USER_ERROR, '添加流程记录失败，请稍候重试~');
}

//更改流程状态(递归)
function set_workflow_status_inner_func($request_data, array $workflow_configs, Model &$model) {
    if (isset($request_data->opt) && str_equals($request_data->opt, 'stop')) {
        $model->set_status(0);
    } else {
        $request_data->status = ((int) $request_data->status) + 1;
        $workflow_step = $workflow_configs['steps'];
        $workflow_config = $workflow_step[$request_data->status];
        if (isset($workflow_config)) {
            $model->set_status($request_data->status);
        } else {
            set_workflow_status_inner_func($request_data, $workflow_configs, $model);
        }
    }
}

//获取流程当前状态配置信息
function get_workflow(array $workflow_configs, &$model, $roletype) {
    $opts_keys = $workflow_configs['opts'];
    $opts = array();
    foreach ($opts_keys as $opt) {
        $opts[$opt] = false;
    }
    $model['status_text'] = '完成';
    $model['status_style'] = 'label label-info';
    $workflow_step = $workflow_configs['steps'][$model['status']];
    if (isset($workflow_step)) {
        $model['status_text'] = $workflow_step['text'];
        $model['status_style'] = $workflow_step['style'];
        $role_opts = $workflow_step['opts'][$roletype];
        if ($model['userid'] > 0 && $model['userid'] != request_login_userid()) {
            $role_opts = array_filter($role_opts, function($opt) {
                return !in_array($opt, array('selfOk', 'selfDelete', 'selfEdit'));
            });
        }
        if ($model['status'] == 0 && $model['userid'] == request_login_userid()) {
            $opts['selfOk'] = true;
            $opts['selfDelete'] = true;
            $opts['selfEdit'] = true;
        }
        if (isset($role_opts)) {
            array_walk($opts, function(&$value, $key) use($role_opts) {
                if (in_array($key, $role_opts)) $value = true;
            });
        }
        if (str_equals($roletype, RoleType::HrManager)) $opts['hrManagerDelete'] = true;
    }
    $model['opts'] = $opts;
}

//发送流程提醒消息
function send_workflow_msg($request_data, array $workflow_configs, Model $model) {
    $workflow_step_role_map = array(
        '待总经理审核' => '总经理',
        '待副总经理审核' => '副总经理',
        '待行政审核' => '行政',
        '待财务审核' => '财务',
        '待部门经理审核' => '部门经理'
    );
    $workflow_name_map = array(
        'adjustrest' => array('name' => '调休', 'type' => 12),
        'causeleave' => array('name' => '请假', 'type' => 13),
        'resign' => array('name' => '补签', 'type' => 14),
        'expense' => array('name' => '费用报销', 'type' => 15),
        'borrowmoney' => array('name' => '借款', 'type' => 16),
        'drawmoney' => array('name' => '领款', 'type' => 17),
        'apply' => array('name' => '申购', 'type' => 18)
    );
    $workflow_name = $workflow_name_map[$workflow_configs['name']];
    $msg_array = array(
        'Content' => '',
        'Title' => $workflow_name['name'] . '流程消息',
        'UserName' => $request_data->username,
        'Type' => $workflow_name['type'],
        'IconName' => '',
        'Code' => 0,
        'MsgType' => 10);

    $workflow_step = $workflow_configs['steps'];
    $workflow_config = $workflow_step[$model->get_status()];
    $workflow_config_previous = $workflow_step[$request_data->previous_status];
    $workflow_step_role = $workflow_step_role_map[$workflow_config_previous['text']];

    if ($model->get_status() == 0) {
        //给本人发送消息
        $msg_array['Content'] = '很抱歉，您的' . $workflow_name['name'] . '申请已被' . $workflow_step_role . '驳回~';
        $msg_array['IconName'] = 'face_ww_shaxiao';
        send_push_msg(json_encode($msg_array), $request_data->userid);
        return;
    }
    if (str_equals($workflow_config['text'], '完成')) {
        //给本人发送消息
        $msg_array['Content'] = '恭喜，您的' . $workflow_name['name'] . '申请已通过' . $workflow_step_role . '终审~';
        $msg_array['IconName'] = 'face_ww_gongxi';
        send_push_msg(json_encode($msg_array), $request_data->userid);
    } elseif (str_equals($workflow_config['text'], '待本人确认')) {
        
    } elseif (str_equals($workflow_config['text'], '待部门主管审核')) {
        //为软件部特殊处理
        if ($request_data->dept1_id == 11) {
            $role_type = get_role_type($request_data->userid);
            if (str_equals($role_type, RoleType::DeptManager1)) {
                $users = array($request_data->userid); //发送给自己
            } else {
                $users = get_dept_manager_userids($request_data->dept1_id);
                $users = array_filter($users, function($u) {
                    return $u != 161;  //移除软件部经理王作湘
                });
            }
            $msg_array['Content'] = $request_data->username . '的' . $workflow_name['name'] . '申请待您审核~';
            $msg_array['IconName'] = 'face_ww_tianshi';
            if (empty($users)) return;
            send_push_msg(json_encode($msg_array), implode(',', $users));
        }
        //为实物部特殊处理
//        if ($request_data->dept1_id == 5) {
//            $role_type = get_role_type($request_data->userid);
//            if (str_equals($role_type, RoleType::DeptManager1)) {
//                $users = array($request_data->userid); //发送给自己
//            } else {
//                $users = get_dept_manager_userids($request_data->dept1_id);
//                $users = array_filter($users, function($u) {
//                    return $u != 122;  //移除实物部经理杨石松
//                });
//            }
//            $msg_array['Content'] = $request_data->username . '的' . $workflow_name['name'] . '申请待您审核~';
//            $msg_array['IconName'] = 'face_ww_tianshi';
//            if (empty($users)) return;
//            send_push_msg(json_encode($msg_array), implode(',', $users));
//        }
    } elseif (str_equals($workflow_config['text'], '待部门经理审核')) {
        if ($request_data->dept1_id == 2) {
            $users = get_userid_by_role_type(4);
        }
        //为软件部特殊处理
        elseif ($request_data->dept1_id == 11) {
            //if (str_equals($role_type, RoleType::DeptManager1)) {
            $users = array(161); //固定为软件部经理王作湘接收，userid=161
            //}
        }
        //为实物部特殊处理
//        elseif ($request_data->dept1_id == 5) {
//            //if (str_equals($role_type, RoleType::DeptManager1)) {
//            $users = array(122); //固定为实物部经理杨石松接收，userid=122
//            //}
//        } 
        else {
            $users = get_dept_manager_userids($request_data->dept1_id);
        }
        $msg_array['Content'] = $request_data->username . '的' . $workflow_name['name'] . '申请待您审核~';
        $msg_array['IconName'] = 'face_ww_tianshi';
        if (empty($users)) return;
        send_push_msg(json_encode($msg_array), implode(',', $users));
    } else {
        $step_role_type_map = array(
            '待总经理审核' => 2,
            '待副总经理审核' => 3,
            '待行政审核' => 4,
            '待财务审核' => 5
        );
        $role_type = $step_role_type_map[$workflow_config['text']];
        if (!isset($role_type)) return;
        //给下一个审核人发送消息
        $users = get_userid_by_role_type($role_type);
        if (empty($users)) return;
        $msg_array['Content'] = $request_data->username . '的' . $workflow_name['name'] . '申请待您审核~';
        $msg_array['IconName'] = 'face_ww_tianshi';
        send_push_msg(json_encode($msg_array), implode(',', $users));
    }
}

//获取流程对应状态的消息接收人用户ID数组
function get_workflow_handlers($request_data, array $workflow_configs, Model $model) {
    if ($model->get_status() == 0) return array($request_data->userid);
    $workflow_step = $workflow_configs['steps'];
    $workflow_config = $workflow_step[$model->get_status()];

    if (str_equals($workflow_config['text'], '完成')) {
        return array($request_data->userid);
    } elseif (str_equals($workflow_config['text'], '待本人确认')) {
        return array();
    } elseif (str_equals($workflow_config['text'], '待部门经理审核')) {
        if ($request_data->dept1_id == 2) {
            return get_userid_by_role_type(4);
        } else {
            return get_dept_manager_userids($request_data->dept1_id);
        }
    } else {
        $step_role_type_map = array(
            '待总经理审核' => 2,
            '待副总经理审核' => 3,
            '待行政审核' => 4,
            '待财务审核' => 5
        );
        $role_type = $step_role_type_map[$workflow_config['text']];
        return get_userid_by_role_type($role_type);
    }
}

//根据role_type获取用户ID数组
function get_userid_by_role_type($role_type) {
    $managers = get_managers();
    $managers = array_filter($managers, function($user) use($role_type) {
        return $user['role_type'] == $role_type;
    });
    return array_keys($managers);
}

//根据user_id获取部门
function get_dept_by_user_id($user_id) {
    $users = get_employees()[$user_id];
    $dept1_id = $users['dept1_id'];
    $dept1 = get_depts()[$dept1_id];

    $dept['dept_id'] = $dept1_id;
    $dept['dept'] = $dept1['text'];
    return $dept;
}

//根据一级部门ID获取用户ID数组
function get_dept_manager_userids($dept1_id) {
    $managers = get_managers();
    $managers = array_filter($managers, function($user) use($dept1_id) {
        return $user['dept1_id'] == $dept1_id;
    });
    return array_keys($managers);
}

function get_workflow_type_by_name($name) {
//记录类型:{1:行政处罚,2:调休,3:请假,4:补卡(补签),5:费用报销,6:借款,7:领款,8:申购} 
    $workflow_type_name_map = array(
        'penalty' => 1,
        'adjustrest' => 2,
        'causeleave' => 3,
        'resign' => 4,
        'expense' => 5,
        'borrowmoney' => 6,
        'drawmoney' => 7,
        'apply' => 8
    );
    return $workflow_type_name_map[$name];
}

function get_workflow_opt($workflow_type, $workflow_status) {
    if ($workflow_type == 1) {
        if ($workflow_status == 0) return '发起';
        elseif ($workflow_status == 1) return '确认';
        else return '通过';
    } else {
        if ($workflow_status == 1) return '提交';
        elseif ($workflow_status == 0) return '驳回';
//elseif ($workflow_status == 3 && $workflow_type == 1 || $workflow_status == 4 && $workflow_type == 8 || $workflow_status == 5 && $workflow_type != 1 && $workflow_type != 8) return '完成';
        else return '通过';
    }
}

//获取销售统计消息接收人ID数组
function get_receive_sale_msg_userids($dept1_id) {
    $receive_role_ids = array(101, 102, 103, 801, 802, 803, 804, 817, 818, 901, 902, 1301);
    if ($dept1_id == 7) {
        $receive_role_ids = array_merge($receive_role_ids, array(601, 602));
    } elseif ($dept1_id == 11) {
        
    }
    $employees = get_employees();
    $employees = array_filter($employees, function($user) use($receive_role_ids, $dept1_id) {
        return in_array($user['role_id'], $receive_role_ids) || $user['dept1_id'] == $dept1_id;
    });
    $receive_userids = array_keys($employees);
    //$receive_userids = array(123, 126, 133, 134); //这是技术几个兄弟的用户ID
    return array_merge($receive_userids);
}

function get_employee_by_dept($dept1_id) {
    $employees = get_employees();
    $employees = array_filter($employees, function($user) use($dept1_id) {
        return $user['dept1_id'] == $dept1_id;
    });
    return $employees;
}

function get_shares_by_month($month) {
    $shares_month_map = array(
        60 => 0.023,
        48 => 0.018,
        42 => 0.015,
        36 => 0.012,
        30 => 0.010,
        24 => 0.008,
        18 => 0.006,
        12 => 0.004,
        6 => 0.003,
        3 => 0.002,
        1 => 0.001
    );
    foreach ($shares_month_map as $key => $value) {
        if ($month >= $key) return $value;
    }
}

function get_commission($amount, $lv) {
    $shares_month_map = array(
        6000 => array('S' => 10, 'A' => 8, 'B' => 6, 'C' => 4),
        5500 => array('S' => 9, 'A' => 8, 'B' => 6, 'C' => 4),
        5000 => array('S' => 8, 'A' => 7, 'B' => 5.3, 'C' => 3.6),
        4500 => array('S' => 7, 'A' => 6, 'B' => 4.6, 'C' => 3.2),
        4000 => array('S' => 6, 'A' => 5, 'B' => 3.9, 'C' => 2.8),
        3500 => array('S' => 5, 'A' => 4, 'B' => 3.2, 'C' => 2.4),
        3000 => array('S' => 4, 'A' => 3, 'B' => 2.5, 'C' => 2),
        2500 => array('S' => 3, 'A' => 2.4, 'B' => 2, 'C' => 1.6),
        2000 => array('S' => 2, 'A' => 1.7, 'B' => 1.5, 'C' => 1.2),
        1500 => array('S' => 1, 'A' => 1, 'B' => 1, 'C' => 0.8),
        0 => array('S' => 0.5, 'A' => 0.5, 'B' => 0.5, 'C' => 0.5)
    );
    foreach ($shares_month_map as $key => $value) {
        if ($amount >= $key) {
            return sprintf("%.2f", $value[$lv] / 100 * $amount);
        }
    }
}

function get_soft_commission($amount, $lv) {
    $shares_month_map = array(
        7000 => array('S' => 6, 'S-1' => 5.5, 'A' => 5, 'B' => 4.5, 'C' => 4, 'C-1' => 3.5),
        6500 => array('S' => 5.5, 'S-1' => 5.5, 'A' => 5, 'B' => 4.5, 'C' => 4, 'C-1' => 3.5),
        6000 => array('S' => 5, 'S-1' => 5, 'A' => 5, 'B' => 4.5, 'C' => 4, 'C-1' => 3.5),
        5500 => array('S' => 4.5, 'S-1' => 4.5, 'A' => 4.5, 'B' => 4.5, 'C' => 4, 'C-1' => 3.5),
        5000 => array('S' => 4, 'S-1' => 4, 'A' => 4, 'B' => 4, 'C' => 4, 'C-1' => 3.5),
        4500 => array('S' => 3.5, 'S-1' => 3.5, 'A' => 3.5, 'B' => 3.5, 'C' => 3.5, 'C-1' => 3.5),
        4000 => array('S' => 3, 'S-1' => 3, 'A' => 3, 'B' => 3, 'C' => 3, 'C-1' => 3),
        3500 => array('S' => 2.5, 'S-1' => 2.5, 'A' => 2.5, 'B' => 2.5, 'C' => 2.5, 'C-1' => 2.5),
        3000 => array('S' => 2, 'S-1' => 2, 'A' => 2, 'B' => 2, 'C' => 2, 'C-1' => 2),
        2500 => array('S' => 1.5, 'S-1' => 1.5, 'A' => 1.5, 'B' => 1.5, 'C' => 1.5, 'C-1' => 1.5),
        2000 => array('S' => 1, 'S-1' => 1, 'A' => 1, 'B' => 1, 'C' => 1, 'C-1' => 1),
        1 => array('S' => 0.5, 'S-1' => 0.5, 'A' => 0.5, 'B' => 0.5, 'C' => 0.5, 'C-1' => 0.5),
        0 => array('S' => 0, 'S-1' => 0, 'A' => 0, 'B' => 6, 'C' => 0, 'C-1' => 0),
    );
    foreach ($shares_month_map as $key => $value) {
        if ($amount >= $key) {
            return sprintf("%.2f", $value[$lv] / 100 * $amount);
        }
    }
}

//推送消息通用方法
function send_push_msg($msg, $users = null) {
    $action = 1;
    $time = date('Y-m-d H:i:s');
    $sign_str = $time . $msg . $action . PUSH_MESSAGE_PRIVATE_KEY;
    if (strlen($users) > 0) $sign_str.=$users;
    $sign = md5($sign_str);
    return curl_http_post(PUSH_MESSAGE_URL, array('caller' => PUSH_MESSAGE_CALLER, 'time' => $time, 'msg' => $msg, 'action' => $action, 'sign' => $sign, 'users' => $users));
}

//发送邮件
function send_email($email, $subject = '星密码OA系统-新流程消息', $body = '您有新流程消息，点击查看') {
    $mail_username = 'youchengadmin@163.com';
    $mail_password = 'yc...oa160';
    $mail_host = 'smtp.163.com';

    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = $mail_host;
    $mail->SMTPAuth = true;
    $mail->Username = $mail_username;
    $mail->Password = $mail_password;

    $mail->CharSet = "UTF-8";
    $mail->From = $mail_username;
    $mail->FromName = '星密码OA系统-admin';
    $mail->addAddress($email);

    $mail->WordWrap = 50;
    $mail->isHTML(true);

    $mail->Subject = $subject;
    $mail->Body = $body;

    if ($mail->send()) {
        return true;
    }
}

function internal_conv_value($type, $value) {
    if (strcasecmp('int', $type) === 0) {
        $value = intval($value);
    } elseif (strcasecmp('float', $type) === 0) {
        $value = floatval($value);
    } elseif (strcasecmp('bool', $type) === 0) {
        $value = (bool) $value;
    } elseif (strcasecmp('date', $type) === 0 || strcasecmp('datetime', $type) === 0) {
//        if (isset($value) && !($value instanceof \DateTime)) {
//            $result = parse_date($value);
//            if (!$result[0]) die_error(RUNTIME_ERROR, "给对象 $this->class 日期字段 $field_name 赋值时失败，$result[msg]");
//            $value = $result['date'];
//        }
    }
    return $value;
}

function get_manager_role_ids() {
    return array(602, 703, 704, 710, 713, 714, 715, 802, 803, 804, 805, 806, 809, 818, 1102, 1103, 1112);
}

/**
 * 创建where 条件SQL 每天3:00 到 第二天2:59:59 为一天的数据
 * @param string $sa 数据库别名
 * @return type 构建完成后的SQL
 */
function getWhereSql($sa = "", $time = '') {
    $h = (int) date("H");
    if (!isset($sa)) $sa = "ps";
    if ($h < 3) {
        return " '" . date('Y-m-d', strtotime("-1 day")) . " 03:00:00' <=  DATE_FORMAT(" . $sa . ".addtime,'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(" . $sa . ".addtime,'%Y-%m-%d %H:%i:%s') <= '" . date('Y-m-d') . " 02:59:59' ";
    } else {
        return " '" . date('Y-m-d') . " 03:00:00' <=  DATE_FORMAT(" . $sa . ".addtime,'%Y-%m-%d %H:%i:%s') AND DATE_FORMAT(" . $sa . ".addtime,'%Y-%m-%d %H:%i:%s') <= '" . date('Y-m-d', strtotime("+1 day")) . " 02:59:59' ";
    }
}

/**
 *  销售统计模块 判断是否为管理员
 * @param type $type 1:星密码 2:软件部
 */
function is_manager($login_userid, $type = 1) {
    if ($type == 1) {
        $manager_role_ids = array(0, 101, 102, 103, 402, 403, 404, 408, 601, 602, 701, 702, 713, 714, 715, 801, 802, 901, 902, 1102, 1103, 805);
        $manager_userids = array(43, 187, 38, 39, 48, 42, 52, 76, 139, 161, 291, 369, 158, 254, 365, 269, 124, 137, 405, 431, 441, 426, 436, 155, 540, 400, 526);
        $manager_dept = array(1);
        return in_array(get_role_id(), $manager_role_ids) || in_array($login_userid, $manager_userids) || in_array(get_dept_id(), $manager_dept);
    } else if ($type == 2) {
        $manager_role_ids_soft = array(0, 101, 102, 103, 402, 403, 404, 408, 601, 602, 701, 702, 801, 802, 901, 902, 1101, 1102, 1103, 1104, 1105, 1112);
        $manager_userids_soft = array(16, 187, 273, 291, 369, 124, 458, 441, 444, 489, 436, 155, 400, 526);
        return in_array(get_role_id(), $manager_role_ids_soft) || in_array($login_userid, $manager_userids_soft);
    }
}

//判断是否是当天
function is_today($date) {
    $today_timestamp = strtotime(date("Y-m-d")) + 3 * 3600;
    $date_timestamp = strtotime($date);
    return $date_timestamp >= $today_timestamp;
}

//日期格式转换
function date_format_conversion($date) {
    return date("Y-m-d", strtotime($date));
}

//判断时间是否是属于第二天凌晨0点到3点以内，如果是的话按业务要求将其算作当天
function date_operation_for_business($date_timestamp) {
    $date = date("Y-m-d", $date_timestamp);
    $today_timestamp = strtotime($date) + 3 * 3600;
    $timestamp = 0;
    if ($date_timestamp < $today_timestamp) {
        $timestamp = 24 * 3600;
    }
    return date("Y-m-d", ($date_timestamp - $timestamp));
}
