<?php

/**
 * 员工列表/员工增删改操作
 *
 * @author ChenHao
 * @copyright 2015 星密码
 * @version 2015/1/27
 */
use Models\Base\Model;
use Models\M_User;
use Models\M_UserExt;
use Models\M_UserToken;
use Models\M_Share;
use Models\Base\SqlSortType;
use Models\Base\SqlOperator;
use Common\ExtNumeric;
use Models\p_customerrecord;
use Models\p_customerrecord_soft;

require '../../Common/ExportData2Excel.php';
require '../../application.php';
require '../../loader-api.php';

$action = request_action();
execute_request(HttpRequestMethod::Get, function() use($action) {
    if ($action == 11) {
        $ex_type = request_int('type');
        $user = new M_User();
        $user->set_where_and(M_User::$field_username, SqlOperator::NotEquals, 'admin');
        $user->set_where_and(M_User::$field_employee_no, SqlOperator::Like, '%YC%');
        $user->set_where_and(M_User::$field_username, SqlOperator::NotLike, '%测试用户%');
        $user->set_order_by(M_User::$field_dept1_id, SqlSortType::Asc);
        $export = new ExportData2Excel();
        if ($ex_type == 1) {
            $user->set_where_and(M_User::$field_status, SqlOperator::In, array(1, 2, 4));
            $field = array('employee_no', 'truename', 'nickname', 'dept1_id', 'role_id', 'join_time', 'positive_time', 'sex', 'birthday', 'age', 'idcard', 'phone', 'qq', 'emergency_phone', 'emergency_contact', 'address', 'hukou', 'work_days', 'work_age', 'work_shares', 'position_shares', 'sanction_shares', 'current_shares', 'quitProve', 'truename', 'qyqq');
            $title_array = array('员工编号', '真实', '花名', '部门', '职位', '入职时间', '转正时间', '性别', '生日', '年龄', '身份证号', '电话', 'QQ', '紧急联系人电话', '紧急联系人及关系', '住址', '户口性质', '工龄(天)', '工龄(月)', '工龄股份(%)', '职位股份(%)', '奖罚股份(%)', '当前股份(%)', '是否提交离职证明', '真实姓名', '企业QQ');
        } else if ($ex_type == 2) {
            $user->set_where_and(M_User::$field_status, SqlOperator::In, array(1, 2, 4));
            $field = array('employee_no', 'username', 'dept1_id', 'role_id', 'phone', 'qq');
            $title_array = array('员工编号', '姓名', '部门', '职位', '电话', 'QQ');
        } else if ($ex_type == 3) {
            $field = array('employee_no', 'truename', 'nickname', 'dept1_id', 'role_id', 'join_time', 'positive_time', 'leave_time', 'sex', 'birthday', 'age', 'idcard', 'phone', 'qq', 'emergency_phone', 'emergency_contact', 'address', 'hukou', 'work_days', 'work_age', 'work_shares', 'position_shares', 'sanction_shares', 'current_shares', 'quitProve', 'truename', 'qyqq');
            $title_array = array('员工编号', '真实', '花名', '部门', '职位', '入职时间', '转正时间', '离职时间', '性别', '生日', '年龄', '身份证号', '电话', 'QQ', '紧急联系人电话', '紧急联系人及关系', '住址', '户口性质', '工龄(天)', '工龄(月)', '工龄股份(%)', '职位股份(%)', '奖罚股份(%)', '当前股份(%)', '是否提交离职证明', '真实姓名', '企业QQ');
            $user->set_where_and(M_User::$field_status, SqlOperator::Equals, 3);
        }
        $user->set_query_fields($field);
        $db = create_pdo();
        $result = Model::query_list($db, $user, NULL, true);
        if (!$result[0]) {
            $export->create(array('导出错误'), array(array('员工数据导出失败,请稍后重试!')), "员工数据导出", "员工数据");
        }
        $depts = get_depts();
        $roles = get_roles();
        $models = Model::list_to_array($result['models'], array(), function (&$d) use($depts, $roles) {
                    $d['dept1_id'] = $depts[$d['dept1_id']]['text'];
                    $d['role_id'] = $roles[$d['role_id']]['text'];
                    $d['sex'] = ($d['sex'] == 0) ? '女' : '男';
                    $d['quitProve'] = ($d['quitProve'] == 0) ? '否' : '是';
                    $d['truename1'] = $d['truename'];
                    $d['qyqq_mail'] = str_length($d['qyqq']) > 0 ? $d['qyqq'] . '@qq.com' : '';
                });
        array_push($field, 'qyqq_mail');
        array_push($title_array, '企业QQ邮箱');
        $export->set_field($field);
        $export->create($title_array, $models, "员工数据导出", "员工数据");
    }
    $user = new M_UserExt();
    $status = request_int('status');
    $sort = request_string('sort');
    $sortname = request_string('sortname');
    $deptid = request_int('deptid');
    $searchName = request_string('searchName');
    $mouth = request_string('mouth');
    filter_numeric($status, 0);
    switch ($action) {
        case 1:
            $user->set_query_fields(array('employee_no', 'username', 'truename', 'nickname', 'userid', 'dept1_id', 'dept2_id', 'role_id', 'work_age', 'phone', 'idcard', 'join_time', 'birthday', 'sex', 'status', 'qq', 'age', 'join_time', 'leave_time', 'positive_time', 'address', 'hukou', 'emergency_contact', 'emergency_phone', 'remark', 'quitProve'));
            if (isset($mouth)) {
                $user->set_custom_where(' AND MONTH(birthday) = ' . $mouth . ' ');
            }
            $user->set_where_and(M_UserExt::$field_status, SqlOperator::NotEquals, '3');
            break;
        case 2:
            $user->set_query_fields(array('employee_no', 'username', 'userid', 'dept1_id', 'dept2_id', 'role_id', 'phone', 'qq', 'sex'));
            $user->set_where_and(M_UserExt::$field_status, SqlOperator::NotEquals, '3');

            $login_user = get_employees()[request_login_userid()];
            if ($login_user['role_type'] == 0 && !in_array($login_user['role_id'], get_manager_role_ids())) {
                $user->set_where_and(M_User::$field_dept1_id, SqlOperator::Equals, $login_user['dept1_id']);
            }

            break;
        case 3:
            $user->set_query_fields(array('employee_no', 'username', 'userid', 'dept1_id', 'dept2_id', 'role_id', 'work_age', 'join_time', 'work_shares', 'position_shares', 'sanction_shares', 'current_shares', 'status'));
            $user->set_where_and(M_UserExt::$field_status, SqlOperator::NotEquals, '3');
            break;
        case 4:
            $user->set_query_fields(array('employee_no', 'username', 'userid', 'dept1_id', 'dept2_id', 'role_id', 'phone', 'idcard', 'join_time', 'birthday', 'sex', 'status', 'qq', 'age', 'join_time', 'leave_time', 'address', 'hukou', 'emergency_contact', 'emergency_phone', 'remark'));
            $user->set_where(M_UserExt::$field_status, SqlOperator::Equals, 3);
            if (!isset($sort) && !isset($sortname)) $user->set_order_by(M_User::$field_leave_time, 'DESC');
            break;
    }
    if ($status > 0 && $action != 4) {
        $user->set_where(M_UserExt::$field_status, SqlOperator::Equals, $status);
    }

    //排除测试用户和管理员用户
    $user->set_where_and(M_UserExt::$field_status, SqlOperator::GreaterThan, -1);
    $user->set_where_and(M_UserExt::$field_username, SqlOperator::NotEquals, 'admin');
    $user->set_where_and(M_UserExt::$field_dept1_id, SqlOperator::NotEquals, 500);

    if (isset($deptid)) {
        $user->set_where_and(M_User::$field_dept1_id, SqlOperator::Equals, $deptid);
    }
    if (isset($searchName)) {
        $user->set_custom_where(" AND ( nickname like '%" . $searchName . "%' OR truename like '%" . $searchName . "%' OR username like '%" . $searchName . "%' OR  employee_no like '%" . $searchName . "%' )");
    }
    if (isset($sort) && isset($sortname)) {
        $user->set_order_by($user->get_field_by_name($sortname), $sort);
    } else {
        $user->set_order_by(M_User::$field_dept1_id, 'ASC');
        $user->set_order_by(M_User::$field_userid, 'ASC');
    }
    $user->set_limit_paged(request_pageno(), request_pagesize());

    $db = create_pdo();
    $result = Model::query_list($db, $user, NULL, true);
    if (!$result[0]) die_error(USER_ERROR, '获取员工资料失败，请重试');
    $models = Model::list_to_array($result['models'], array(), "id_2_text");
    echo_list_result($result, $models);
});

execute_request(HttpRequestMethod::Post, function() use($action) {
    $userData = request_object();
    //添加员工信息
    if ($action == 1) {
        unset($userData->dept2_id);
        unset($userData->leave_time);
        $user = new M_User();
        $user->set_field_from_array($userData);
        $user->set_dept2_id($userData->dept1_id);
        $join_date = $userData->join_time;
        $user->set_work_age(time_diff_unit_floor($join_date, 'now', 'm'));
        //$user->set_username($userData->truename . "(" . $userData->nickname . ")");
        $user->set_username($userData->nickname);
        $db = create_pdo();
        pdo_transaction($db, function($db) use($user) {
            $result = $user->insert($db);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存员工资料失败。' . $result['detail_cn'], $result);
            $userToken = new M_UserToken($user->get_id());
            $userToken->set_employee_no($user->get_employee_no());
            $userToken->set_username($user->get_username());
            $userToken->set_password(md5_16('888888')); //默认登录密码888888
            $userToken->set_token(build_token($user->get_id(), $userToken->get_password()));
            $userToken->set_status($user->get_status());
            $result = $userToken->insert($db);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存员工基本信息失败', $result);
        });
        echo_msg('添加成功');
    }
    //修改员工信息
    if ($action == 2) {
        $user = new M_User($userData->userid);
        $db = create_pdo();
        $user->set_field_from_array($userData);
        $user->set_work_age(time_diff_unit_floor($userData->join_time, 'now', 'm'));
        //$user->set_username($userData->truename . "(" . $userData->nickname . ")");
        // $user->set_username(str_length($userData->nickname) > 0 ? $userData->nickname : $userData->truename);
        $user->set_username($userData->nickname);
        //判断售后是否离职，特别是双人组的时候，如果有一个人离职了，就将他们撤成单人组(用于销售和软件部门售后统计功能模块)
        if ($userData->status == 3) {
            if ($userData->dept1_id == 6) {
                split_double_group($userData->userid);
            } elseif ($userData->dept1_id == 11) {
                split_double_group_soft($userData->userid);
            }
        }
        if (isset($userData->truename) || isset($userData->status) || isset($userData->nickname)) {
            $userToken = new M_UserToken($user->get_id());
            $userToken->set_employee_no($user->get_employee_no());
//            if (str_equals($userData->nickname, "")) {
//                $userToken->set_username($userData->truename);
//            } else {
//                $userToken->set_username($userData->nickname);
//            }
            $userToken->set_username($userData->nickname);
            $userToken->set_status($user->get_status());
        }
        //添加更改记录
        add_data_change_log($db, $userData, new M_User($userData->userid), 1);

        pdo_transaction($db, function($db) use($user, $userToken) {
            $result = $user->update($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存员工资料失败。' . $result['detail_cn'], $result);
            if (isset($userToken)) {
                $result = $userToken->update($db, true);
                if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存员工基本信息失败。' . $result['detail_cn'], $result);
            }
        });

        echo_msg('保存成功');
    }
    //员工离职
    if ($action == 3) {
        $employeeids = request_string('employeeids');
        $user = new M_User();
        $user->set_where_and(M_User::$field_userid, SqlOperator::In, explode(',', $employeeids));
        $user->set_status(3);

        $userToken = new M_UserToken();
        $userToken->set_where_and(M_UserToken::$field_userid, SqlOperator::In, explode(',', $employeeids));
        $userToken->set_status(3);
        $db = create_pdo();

        pdo_transaction($db, function($db) use($user, $userToken) {
            $result = $user->update($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存员工资料失败。' . $result['detail_cn'], $result);
            $result = $userToken->update($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '保存员工基本信息失败。' . $result['detail_cn'], $result);
        });

        echo_msg('修改成功');
    }
    //更新员工股份
    if ($action == 4) {
        $users = new M_User();
        $users->set_where_and(M_User::$field_status, SqlOperator::GreaterThan, -1);
        $users->set_where_and(M_User::$field_username, SqlOperator::NotEquals, 'admin');
        $db = create_pdo();
        $employees = Model::query_list($db, $users);
        if (!$employees) die_error(USER_ERROR, '更新员工股份信息失败~');
        $employees = Model::list_to_array($employees['models']);
        $roles = get_roles();
        pdo_transaction($db, function($db) use($employees, $roles) {
            foreach ($employees as $employee) {
                $userid = $employee['userid'];
                //if ($userid == 1) continue;
                $work_days = time_diff_unit_floor($employee['join_time'], 'now', 'd');
                $work_month = time_diff_unit_floor($employee['join_time'], 'now', 'm');
                $work_shares = get_shares_by_month($work_month);
                //if (in_array($userid, array(2, 3, 124))) $work_shares = 0;
                $role_shares = $roles[$employee['role_id']]['shares'];
                $total_shares = new ExtNumeric(0, 3);
                $total_shares->add($work_shares);
                $total_shares->add($role_shares);
                $user = new M_User($userid);
                //工龄
                $user->set_work_days($work_days);
                $user->set_work_age($work_month);
                //工龄股份
                $user->set_work_shares($work_shares);
                //职位股份
                $user->set_position_shares($role_shares);
                //奖惩股份
                //$user->set_sanction_shares($sanction_shares);
                //当前股份
                $user->set_current_shares($total_shares->getValue());
                $result = $user->update($db, true);
                if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '更新员工股份数据失败', $result);
            }
        });
        echo_msg('更新成功');
    }
    if ($action == 5) {
        $user = new M_User();
        $userToken = new M_UserToken($userData->userid);
        $user->set_field_from_array($userData);
        $db = create_pdo();
        pdo_transaction($db, function($db) use($user, $userToken) {
            $result = $user->delete($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '删除员工数据失败', $result);
            $result = $userToken->delete($db, true);
            if (!$result[0]) throw new TransactionException(PDO_ERROR_CODE, '删除员工数据失败', $result);
        });
        $result = $user->delete($db, true);
        if (!$result[0]) die_error(USER_ERROR, '删除员工资料失败');
        echo_msg('删除成功');
    }

    /**
     * 密码重置
     */
    if ($action == 6) {
        $userToken = new M_UserToken($userData->userid);
        $password = generate_password();
        $userToken->set_password(md5_16($password));
        $db = create_pdo();
        $result = $userToken->update($db, true);
        if (!$result[0]) die_error(USER_ERROR, '密码重置失败,请稍后重试~');
        echo_msg("密码重置成功,新密码为:<span style='color:red;'>" . $password . "</span>,请妥善保管~");
    }
});

//信息修改备注累积记录
//        $user_update = (array) $userData;
//        unset($user_update['action']);
//        $user->set_where(M_User::$field_userid, SqlOperator::Equals, $userData->userid);
//        $db = create_pdo();
//        $result = Model::query_list($db, $user, NULL, true);
//        $remark_base = Model::list_to_array($result['models'], array(), "id_2_text");
//        $field_name = array('userid' => '员工编号', 'username' => '员工姓名', 'sex' => '性别', 'dept2_text' => '所属部门', 'role_text' => '职位', 'phone' => '联系电话', 'qq' => 'QQ', 'age' => '年龄', 'idcard' => '身份证号码', 'birthday' => '生日', 'join_time' => '入职时间', 'leave_time' => '离职时间', 'address' => '住址', 'hukou' => '户口性质', 'status' => '状态', 'emergency_contact' => '紧急联系人及关系', 'emergency_phone' => '紧急联系人电话');
//        foreach($user_update as $key=>$value){
//            if(!empty($remark_base)){
//                if ($remark_base[0][$key] != $value) {
//                $nowdate = date('Y-m-d H:i:s', time());
//                $remark_result = $nowdate . ' ' . $field_name[$key] . '：' . $remark_base[0][$key] . '->' . $value.'\n';
//                $remark_text.=$remark_result;
//                }
//            }
//        }      
//        $user->set_remark($remark_text);

function generate_password($length = 8) {
    $chars = '0123456789';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[mt_rand(0, strlen($chars) - 1)];
    }

    return $password;
}

function split_double_group($customer_id) {
    $customerrecord = new p_customerrecord();
    $db = create_pdo();
    $customerrecord->set_where_and(p_customerrecord::$field_userid, SqlOperator::Equals, $customer_id);
    $customerrecord->load($db, $customerrecord);
    $customerrecord_res = $customerrecord->to_array();
    //如果是双人组拆分
    if ($customerrecord_res['suserid']) {
        $customerrecord_owner = new p_customerrecord();
        $customerrecord_owner->set_group('1');
        $customerrecord_owner->set_group_members('0');
        $customerrecord_owner->set_suserid('0');
        $customerrecord_owner->set_where_and(p_customerrecord::$field_userid, SqlOperator::Equals, $customer_id);
        $result = $customerrecord_owner->update($db, true);

        $customerrecord_stuff = new p_customerrecord();
        $customerrecord_stuff->set_group('1');
        $customerrecord_stuff->set_group_members('0');
        $customerrecord_stuff->set_suserid('0');
        $customerrecord_stuff->set_where_and(p_customerrecord::$field_userid, SqlOperator::Equals, $customerrecord_res['suserid']);
        $result = $customerrecord_stuff->update($db, true);
    }
}

function split_double_group_soft($customer_id) {
    $customerrecord = new p_customerrecord_soft();
    $db = create_pdo();
    $customerrecord->set_where_and(p_customerrecord_soft::$field_userid, SqlOperator::Equals, $customer_id);
    $customerrecord->load($db, $customerrecord);
    $customerrecord_res = $customerrecord->to_array();
    //如果是双人组拆分
    if ($customerrecord_res['suserid']) {
        $customerrecord_owner = new p_customerrecord_soft();
        $customerrecord_owner->set_group('1');
        $customerrecord_owner->set_group_members('0');
        $customerrecord_owner->set_suserid('0');
        $customerrecord_owner->set_where_and(p_customerrecord_soft::$field_userid, SqlOperator::Equals, $customer_id);
        $result = $customerrecord_owner->update($db, true);

        $customerrecord_stuff = new p_customerrecord_soft();
        $customerrecord_stuff->set_group('1');
        $customerrecord_stuff->set_group_members('0');
        $customerrecord_stuff->set_suserid('0');
        $customerrecord_stuff->set_where_and(p_customerrecord_soft::$field_userid, SqlOperator::Equals, $customerrecord_res['suserid']);
        $result = $customerrecord_stuff->update($db, true);
    }
}
